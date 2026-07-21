import 'dart:io';

import 'package:dio/dio.dart';
import 'package:flutter_riverpod/flutter_riverpod.dart';

import '../models/material.dart';
import '../models/reservation.dart';
import '../models/space.dart';
import '../models/user.dart';
import 'api_client.dart';

class ReservationsPage {
  final List<Reservation> items;
  final int currentPage;
  final int lastPage;

  ReservationsPage({required this.items, required this.currentPage, required this.lastPage});

  bool get hasMore => currentPage < lastPage;
}

class ReservationsIndex {
  final List<Reservation> pendentes;
  final ReservationsPage page;

  ReservationsIndex({required this.pendentes, required this.page});
}

class ReservationFilters {
  final String? status;
  final int? spaceId;
  final String? dataInicio;
  final String? dataFim;
  final String? busca;

  const ReservationFilters({this.status, this.spaceId, this.dataInicio, this.dataFim, this.busca});

  bool get isEmpty =>
      status == null && spaceId == null && dataInicio == null && dataFim == null && (busca == null || busca!.isEmpty);

  ReservationFilters copyWith({
    String? status,
    bool clearStatus = false,
    int? spaceId,
    bool clearSpaceId = false,
    String? dataInicio,
    bool clearDataInicio = false,
    String? dataFim,
    bool clearDataFim = false,
    String? busca,
  }) {
    return ReservationFilters(
      status: clearStatus ? null : (status ?? this.status),
      spaceId: clearSpaceId ? null : (spaceId ?? this.spaceId),
      dataInicio: clearDataInicio ? null : (dataInicio ?? this.dataInicio),
      dataFim: clearDataFim ? null : (dataFim ?? this.dataFim),
      busca: busca ?? this.busca,
    );
  }

  Map<String, dynamic> toQuery() => {
        if (status != null) 'status': status,
        if (spaceId != null) 'space_id': spaceId,
        if (dataInicio != null) 'data_inicio': dataInicio,
        if (dataFim != null) 'data_fim': dataFim,
        if (busca != null && busca!.isNotEmpty) 'busca': busca,
      };
}

class ReservationResponse {
  final Reservation reservation;
  final String message;

  ReservationResponse({required this.reservation, required this.message});
}

class LoginResult {
  final String token;
  final AppUser user;

  LoginResult({required this.token, required this.user});
}

class SpaceResponse {
  final Space space;
  final String message;

  SpaceResponse({required this.space, required this.message});
}

class MaterialResponse {
  final LabMaterial material;
  final String message;

  MaterialResponse({required this.material, required this.message});
}

class LabRepository {
  final Dio _dio;
  LabRepository(this._dio);

  Future<T> _guard<T>(Future<T> Function() body) async {
    try {
      return await body();
    } on DioException catch (e) {
      throw ApiException.fromDioException(e);
    }
  }

  Future<LoginResult> login(String email, String password) => _guard(() async {
        final res = await _dio.post('/login', data: {
          'email': email,
          'password': password,
          'device_name': 'flutter-app',
        });
        return LoginResult(
          token: res.data['token'] as String,
          user: AppUser.fromJson(res.data['user'] as Map<String, dynamic>),
        );
      });

  Future<void> logout() => _guard(() async {
        await _dio.post('/logout');
      });

  Future<void> registerDeviceToken(String token, {String? platform}) => _guard(() async {
        await _dio.post('/device-tokens', data: {'token': token, 'platform': platform});
      });

  Future<void> unregisterDeviceToken(String token) => _guard(() async {
        await _dio.delete('/device-tokens', data: {'token': token});
      });

  Future<AppUser> me() => _guard(() async {
        final res = await _dio.get('/me');
        return AppUser.fromJson(res.data['data'] as Map<String, dynamic>);
      });

  Future<Map<String, dynamic>> dashboard() => _guard(() async {
        final res = await _dio.get('/dashboard');
        return {
          'stats': Map<String, dynamic>.from(res.data['stats'] as Map),
          'recent': (res.data['recent'] as List<dynamic>)
              .map((r) => Reservation.fromJson(r as Map<String, dynamic>))
              .toList(),
        };
      });

  Future<List<Space>> spaces() => _guard(() async {
        final res = await _dio.get('/spaces');
        return (res.data['data'] as List<dynamic>).map((s) => Space.fromJson(s as Map<String, dynamic>)).toList();
      });

  Future<List<LabMaterial>> materials() => _guard(() async {
        final res = await _dio.get('/materials');
        return (res.data['data'] as List<dynamic>)
            .map((m) => LabMaterial.fromJson(m as Map<String, dynamic>))
            .toList();
      });

  Future<ReservationsIndex> reservationsIndex({ReservationFilters filters = const ReservationFilters(), int page = 1}) =>
      _guard(() async {
        final res = await _dio.get('/reservations', queryParameters: {
          ...filters.toQuery(),
          'page': page,
        });
        final list = res.data['reservations'] as Map<String, dynamic>;
        return ReservationsIndex(
          pendentes: (res.data['pendentes'] as List<dynamic>)
              .map((r) => Reservation.fromJson(r as Map<String, dynamic>))
              .toList(),
          page: ReservationsPage(
            items: (list['data'] as List<dynamic>).map((r) => Reservation.fromJson(r as Map<String, dynamic>)).toList(),
            currentPage: list['meta']['current_page'] as int,
            lastPage: list['meta']['last_page'] as int,
          ),
        );
      });

  Future<ReservationsPage> history({int page = 1}) => _guard(() async {
        final res = await _dio.get('/reservations/history', queryParameters: {'page': page});
        return ReservationsPage(
          items: (res.data['data'] as List<dynamic>).map((r) => Reservation.fromJson(r as Map<String, dynamic>)).toList(),
          currentPage: res.data['meta']['current_page'] as int,
          lastPage: res.data['meta']['last_page'] as int,
        );
      });

  Future<Reservation> show(int id) => _guard(() async {
        final res = await _dio.get('/reservations/$id');
        return Reservation.fromJson(res.data['data'] as Map<String, dynamic>);
      });

  Future<ReservationResponse> create({
    required int spaceId,
    required String reservationDate,
    required String startTime,
    String? endTime,
    required String description,
    Map<int, int> materialQuantities = const {},
  }) =>
      _guard(() async {
        final formData = FormData.fromMap({
          'space_id': spaceId,
          'reservation_date': reservationDate,
          'start_time': startTime,
          if (endTime != null) 'end_time': endTime,
          'description': description,
        });
        for (final entry in materialQuantities.entries) {
          formData.fields.add(MapEntry('mat_ids[]', entry.key.toString()));
          formData.fields.add(MapEntry('mat_qty[${entry.key}]', entry.value.toString()));
        }
        final res = await _dio.post('/reservations', data: formData);
        return _toReservationResponse(res.data);
      });

  Future<ReservationResponse> approve(int id) => _action('/reservations/$id/approve', method: 'PATCH');
  Future<ReservationResponse> reject(int id) => _action('/reservations/$id/reject', method: 'PATCH');
  Future<ReservationResponse> start(int id) => _action('/reservations/$id/start');

  Future<ReservationResponse> professorObs(int id, String obs) =>
      _action('/reservations/$id/professor-obs', data: {'obs': obs});

  Future<ReservationResponse> auxiliarFinalize(int id, String auxiliarObs) =>
      _action('/reservations/$id/auxiliar-finalize', data: {'auxiliar_obs': auxiliarObs});

  Future<ReservationResponse> validateActivity(int id, {String? coordenadorObs}) =>
      _action('/reservations/$id/validate', method: 'PATCH', data: {'coordenador_obs': coordenadorObs});

  Future<ReservationResponse> uploadImage(int id, String type, File photo) => _guard(() async {
        final formData = FormData.fromMap({
          'type': type,
          'photo': await MultipartFile.fromFile(photo.path),
        });
        final res = await _dio.post('/reservations/$id/images', data: formData);
        return _toReservationResponse(res.data);
      });

  Future<ReservationResponse> _action(String path, {String method = 'POST', Map<String, dynamic>? data}) =>
      _guard(() async {
        final res = method == 'PATCH'
            ? await _dio.patch(path, data: data)
            : await _dio.post(path, data: data);
        return _toReservationResponse(res.data);
      });

  ReservationResponse _toReservationResponse(dynamic data) {
    return ReservationResponse(
      reservation: Reservation.fromJson(data['data'] as Map<String, dynamic>),
      message: data['message'] as String? ?? '',
    );
  }

  // ── Administração: espaços, materiais e auxiliares ──

  Future<List<AppUser>> auxiliares() => _guard(() async {
        final res = await _dio.get('/auxiliares');
        return (res.data['data'] as List<dynamic>).map((u) => AppUser.fromJson(u as Map<String, dynamic>)).toList();
      });

  Future<SpaceResponse> createSpace({required String name, String? description, int? auxiliarId}) => _guard(() async {
        final res = await _dio.post('/spaces', data: {
          'name': name,
          'description': description,
          'auxiliar_id': auxiliarId,
        });
        return _toSpaceResponse(res.data);
      });

  Future<SpaceResponse> updateSpace(int id, {required String name, String? description, int? auxiliarId}) =>
      _guard(() async {
        final res = await _dio.post('/spaces/$id/update', data: {
          'name': name,
          'description': description,
          'auxiliar_id': auxiliarId,
        });
        return _toSpaceResponse(res.data);
      });

  Future<void> deleteSpace(int id) => _guard(() async {
        await _dio.delete('/spaces/$id');
      });

  SpaceResponse _toSpaceResponse(dynamic data) => SpaceResponse(
        space: Space.fromJson(data['data'] as Map<String, dynamic>),
        message: data['message'] as String? ?? '',
      );

  Future<MaterialResponse> createMaterial({
    required String name,
    String? description,
    required int stockQuantity,
    String? unit,
    String? patrimonyNumber,
    File? photo,
  }) =>
      _guard(() async {
        final formData = FormData.fromMap({
          'name': name,
          if (description != null) 'description': description,
          'stock_quantity': stockQuantity,
          if (unit != null) 'unit': unit,
          if (patrimonyNumber != null) 'patrimony_number': patrimonyNumber,
          if (photo != null) 'photo': await MultipartFile.fromFile(photo.path),
        });
        final res = await _dio.post('/materials', data: formData);
        return _toMaterialResponse(res.data);
      });

  Future<MaterialResponse> updateMaterial(
    int id, {
    required String name,
    String? description,
    required int stockQuantity,
    String? unit,
    String? patrimonyNumber,
    File? photo,
  }) =>
      _guard(() async {
        final formData = FormData.fromMap({
          'name': name,
          if (description != null) 'description': description,
          'stock_quantity': stockQuantity,
          if (unit != null) 'unit': unit,
          if (patrimonyNumber != null) 'patrimony_number': patrimonyNumber,
          if (photo != null) 'photo': await MultipartFile.fromFile(photo.path),
        });
        final res = await _dio.post('/materials/$id/update', data: formData);
        return _toMaterialResponse(res.data);
      });

  Future<void> deleteMaterial(int id) => _guard(() async {
        await _dio.delete('/materials/$id');
      });

  MaterialResponse _toMaterialResponse(dynamic data) => MaterialResponse(
        material: LabMaterial.fromJson(data['data'] as Map<String, dynamic>),
        message: data['message'] as String? ?? '',
      );
}

final labRepositoryProvider = Provider<LabRepository>((ref) {
  return LabRepository(ref.watch(dioProvider));
});
