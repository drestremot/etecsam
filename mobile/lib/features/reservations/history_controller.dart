import 'package:flutter_riverpod/flutter_riverpod.dart';

import '../../core/lab_repository.dart';
import '../../models/reservation.dart';

class HistoryState {
  final List<Reservation> items;
  final int currentPage;
  final int lastPage;
  final bool isLoading;
  final bool isLoadingMore;
  final String? error;

  const HistoryState({
    this.items = const [],
    this.currentPage = 1,
    this.lastPage = 1,
    this.isLoading = true,
    this.isLoadingMore = false,
    this.error,
  });

  bool get hasMore => currentPage < lastPage;

  HistoryState copyWith({
    List<Reservation>? items,
    int? currentPage,
    int? lastPage,
    bool? isLoading,
    bool? isLoadingMore,
    String? error,
    bool clearError = false,
  }) {
    return HistoryState(
      items: items ?? this.items,
      currentPage: currentPage ?? this.currentPage,
      lastPage: lastPage ?? this.lastPage,
      isLoading: isLoading ?? this.isLoading,
      isLoadingMore: isLoadingMore ?? this.isLoadingMore,
      error: clearError ? null : (error ?? this.error),
    );
  }
}

class HistoryController extends Notifier<HistoryState> {
  @override
  HistoryState build() {
    Future.microtask(refresh);
    return const HistoryState();
  }

  Future<void> refresh() async {
    state = state.copyWith(isLoading: true, clearError: true);
    try {
      final page = await ref.read(labRepositoryProvider).history(page: 1);
      state = state.copyWith(
        items: page.items,
        currentPage: page.currentPage,
        lastPage: page.lastPage,
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
      final page = await ref.read(labRepositoryProvider).history(page: state.currentPage + 1);
      state = state.copyWith(
        items: [...state.items, ...page.items],
        currentPage: page.currentPage,
        lastPage: page.lastPage,
        isLoadingMore: false,
      );
    } catch (e) {
      state = state.copyWith(isLoadingMore: false, error: e.toString());
    }
  }
}

final historyControllerProvider = NotifierProvider<HistoryController, HistoryState>(HistoryController.new);
