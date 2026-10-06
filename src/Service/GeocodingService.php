<?php

namespace Wexample\SymfonyGeo\Service;

use Wexample\SymfonyGeo\Interface\GeocoderInterface;
use Wexample\SymfonyGeo\Interface\GeoLocatedInterface;
use Wexample\SymfonyGeo\Interface\PostalAddressInterface;

/**
 * Places addresses on the map through whichever geocoder the app installed.
 * Without one, nothing is located and nothing fails: coordinates stay what
 * they were, typed in or imported.
 */
class GeocodingService
{
    public function __construct(
        private readonly ?GeocoderInterface $geocoder = null,
    ) {
    }

    public function isAvailable(): bool
    {
        return null !== $this->geocoder;
    }

    /**
     * Asks the geocoder for the address's point, unless it already has one
     * and $force is false. Does not flush.
     *
     * @return bool whether the address holds a point afterwards
     */
    public function locate(
        PostalAddressInterface&GeoLocatedInterface $address,
        bool $force = false
    ): bool {
        if (! $force && null !== $address->getGeoPoint()) {
            return true;
        }

        if (null === $this->geocoder) {
            return null !== $address->getGeoPoint();
        }

        $point = $this->geocoder->geocode($address);

        // An address the geocoder cannot place keeps the point it had.
        if (null !== $point) {
            $address->setGeoPoint($point);
        }

        return null !== $address->getGeoPoint();
    }
}
