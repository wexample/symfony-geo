<?php

namespace Wexample\SymfonyGeo\Interface;

use Wexample\SymfonyGeo\Class\GeoPoint;

/**
 * Something that may sit on a map. Null until located.
 */
interface GeoLocatedInterface
{
    public function getGeoPoint(): ?GeoPoint;

    public function setGeoPoint(?GeoPoint $point): static;
}
