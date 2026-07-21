import 'package:flutter_riverpod/flutter_riverpod.dart';

import '../../core/lab_repository.dart';
import '../../models/reservation.dart';

class ReservationsListState {
  final List<Reservation> pendentes;
  final List<Reservation> items;
  final int currentPage;
  final int lastPage;
  final bool isLoading;
  final bool isLoadingMore;
  final String? error;
  final ReservationFilters filters;

  const ReservationsListState({
    this.pendentes = const [],
    this.items = const [],
    this.currentPage = 1,
    this.lastPage = 1,
    this.isLoading = true,
    this.isLoadingMore = false,
    this.error,
    this.filters = const ReservationFilters(),
  });

  bool get hasMore => currentPage < lastPage;

  ReservationsListState copyWith({
    List<Reservation>? pendentes,
    List<Reservation>? items,
    int? currentPage,
    int? lastPage,
    bool? isLoading,
    bool? isLoadingMore,
    String? error,
    bool clearError = false,
    ReservationFilters? filters,
  }) {
    return ReservationsListState(
      pendentes: pendentes ?? this.pendentes,
      items: items ?? this.items,
      currentPage: currentPage ?? this.currentPage,
      lastPage: lastPage ?? this.lastPage,
      isLoading: isLoading ?? this.isLoading,
      isLoadingMore: isLoadingMore ?? this.isLoadingMore,
      error: clearError ? null : (error ?? this.error),
      filters: filters ?? this.filters,
    );
  }
}

class ReservationsListController extends Notifier<ReservationsListState> {
  @override
  ReservationsListState build() {
    Future.microtask(refresh);
    return const ReservationsListState();
  }

  Future<void> refresh() async {
    state = state.copyWith(isLoading: true, clearError: true);
    try {
      final index = await ref.read(labRepositoryProvider).reservationsIndex(filters: state.filters, page: 1);
      state = state.copyWith(
        pendentes: index.pendentes,
        items: index.page.items,
        currentPage: index.page.currentPage,
        lastPage: index.page.lastPage,
        isLoading: false,
      );
    } catch (e) {
      state = state.copyWith(isLoading: false, error: e.toString());
    }
  }

  Future<void> loadMore() async {
    if (state.isLoadingMore || !state.hasMore) return;
    state = state.copyWith(isLoadingMore: true);
    try {
      final index = await ref
          .read(labRepositoryProvider)
          .reservationsIndex(filters: state.filters, page: state.currentPage + 1);
      state = state.copyWith(
        items: [...state.items, ...index.page.items],
        currentPage: index.page.currentPage,
        lastPage: index.page.lastPage,
        isLoadingMore: false,
      );
    } catch (e) {
      state = state.copyWith(isLoadingMore: false, error: e.toString());
    }
  }

  Future<void> applyFilters(ReservationFilters filters) async {
    state = state.copyWith(filters: filters);
    await refresh();
  }
}

final reservationsListControllerProvider =
    NotifierProvider<ReservationsListController, ReservationsListState>(ReservationsListController.new);
