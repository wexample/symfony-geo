<?php

namespace Wexample\SymfonyGeo\Class;

use InvalidArgumentException;

/**
 * A place on the earth, in WGS 84 degrees: what a map pins, a geocoder
 * answers and a route goes through.
 */
final readonly class GeoPoint
{
    public const float EARTH_RADIUS_METERS = 6_371_008.8;

    public function __construct(
        public float $latitude,
        public float $longitude,
    ) {
        if ($latitude < -90 || $latitude > 90) {
            throw new InvalidArgumentException(sprintf('Latitude %F is out of [-90, 90].', $latitude));
        }

        if ($longitude < -180 || $longitude > 180) {
            throw new InvalidArgumentException(sprintf('Longitude %F is out of [-180, 180].', $longitude));
        }
    }

    /**
     * Great-circle distance (haversine): as the crow flies, not by road.
     */
    public function distanceTo(self $other): float
    {
        $latitudeFrom = deg2rad($this->latitude);
        $latitudeTo = deg2rad($other->latitude);
        $deltaLatitude = $latitudeTo - $latitudeFrom;
        $deltaLongitude = deg2rad($other->longitude - $this->longitude);

        $a = sin($deltaLatitude / 2) ** 2
            + cos($latitudeFrom) * cos($latitudeTo) * sin($deltaLongitude / 2) ** 2;

        return 2 * self::EARTH_RADIUS_METERS * asin(min(1.0, sqrt($a)));
    }

    /**
     * The [longitude, latitude] order of GeoJSON and of every web map.
     *
     * @return array{0: float, 1: float}
     */
    public function toLngLat(): array
    {
        return [$this->longitude, $this->latitude];
    }
}
