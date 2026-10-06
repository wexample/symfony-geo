<?php

namespace Wexample\SymfonyGeo\Interface;

use Wexample\SymfonyGeo\Class\GeoPoint;

/**
 * Turns a postal address into a point. Implemented by a remote package
 * (Nominatim, a commercial geocoder...); geo only states the contract.
 */
interface GeocoderInterface
{
    /**
     * Null when the address matches no place: an unknown address is an
     * answer, not an error. A remote failing is an exception.
     */
    public function geocode(PostalAddressInterface $address): ?GeoPoint;
}
