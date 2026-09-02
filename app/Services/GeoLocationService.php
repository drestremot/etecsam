<?php

namespace App\Services;

class GeoLocationService
{
    /**
     * Calcula a distância em metros entre duas coordenadas geográficas (Fórmula de Haversine).
     */
    public static function calculateDistance(
        float $lat1,
        float $lon1,
        float $lat2,
        float $lon2
    ): int {
        $earthRadius = 6371000; // Raio da Terra em metros

        $latFrom = deg2rad($lat1);
        $lonFrom = deg2rad($lon1);
        $latTo = deg2rad($lat2);
        $lonTo = deg2rad($lon2);

        $latDelta = $latTo - $latFrom;
        $lonDelta = $lonTo - $lonFrom;

        $angle = 2 * asin(sqrt(
            pow(sin($latDelta / 2), 2) +
            cos($latFrom) * cos($latTo) * pow(sin($lonDelta / 2), 2)
        ));

        return (int) round($angle * $earthRadius);
    }

    /**
     * Verifica se uma coordenada está dentro do raio permitido de uma unidade escolar.
     */
    public static function isWithinGeofence(
        float $userLat,
        float $userLon,
        float $unitLat,
        float $unitLon,
        int $radiusMeters = 300
    ): array {
        $distance = self::calculateDistance($userLat, $userLon, $unitLat, $unitLon);
        $isInside = $distance <= $radiusMeters;

        return [
            'is_within'       => $isInside,
            'distance_meters' => $distance,
            'radius_meters'   => $radiusMeters,
        ];
    }
}

