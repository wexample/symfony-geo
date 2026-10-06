<?php

namespace Wexample\SymfonyGeo\Entity\Traits;

use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping\Column;
use Wexample\SymfonyGeo\Class\GeoPoint;

/**
 * Latitude and longitude columns. Implements GeoLocatedInterface.
 *
 * Both are set or both are null: the point is read and written whole.
 */
trait HasGeoPointTrait
{
    #[Column(type: Types::FLOAT, nullable: true)]
    protected ?float $latitude = null;

    #[Column(type: Types::FLOAT, nullable: true)]
    protected ?float $longitude = null;

    public function getGeoPoint(): ?GeoPoint
    {
        if (null === $this->latitude || null === $this->longitude) {
            return null;
        }

        return new GeoPoint($this->latitude, $this->longitude);
    }

    public function setGeoPoint(?GeoPoint $point): static
    {
        $this->latitude = $point?->latitude;
        $this->longitude = $point?->longitude;

        return $this;
    }

    public function isGeoLocated(): bool
    {
        return null !== $this->getGeoPoint();
    }
}
