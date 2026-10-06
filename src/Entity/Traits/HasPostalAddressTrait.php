<?php

namespace Wexample\SymfonyGeo\Entity\Traits;

use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping\Column;
use Doctrine\ORM\Mapping\JoinColumn;
use Doctrine\ORM\Mapping\ManyToOne;
use Wexample\SymfonyGeo\Entity\Country;
use Wexample\SymfonyGeo\Helper\PostalAddressHelper;
use Wexample\SymfonyGeo\Interface\GeoLocatedInterface;
use Wexample\SymfonyGeo\Interface\PostalAddressInterface;

/**
 * Postal address columns for any entity, and the point it sits on once
 * located. Implements PostalAddressInterface and GeoLocatedInterface.
 */
trait HasPostalAddressTrait
{
    use HasGeoPointTrait;

    #[Column(type: Types::STRING, length: 255, nullable: true)]
    protected ?string $postalAddress = null;

    #[Column(type: Types::STRING, length: 60, nullable: true)]
    protected ?string $postCode = null;

    #[Column(type: Types::STRING, length: 255, nullable: true)]
    protected ?string $city = null;

    #[ManyToOne(targetEntity: Country::class)]
    #[JoinColumn(nullable: true, onDelete: 'SET NULL')]
    protected ?Country $country = null;

    public function getPostalAddress(): ?string
    {
        return $this->postalAddress;
    }

    public function setPostalAddress(?string $postalAddress): static
    {
        $this->postalAddress = $postalAddress;

        return $this;
    }

    public function getPostCode(): ?string
    {
        return $this->postCode;
    }

    public function setPostCode(?string $postCode): static
    {
        $this->postCode = $postCode;

        return $this;
    }

    public function getCity(): ?string
    {
        return $this->city;
    }

    public function setCity(?string $city): static
    {
        $this->city = $city;

        return $this;
    }

    public function getCountry(): ?Country
    {
        return $this->country;
    }

    public function setCountry(?Country $country): static
    {
        $this->country = $country;

        return $this;
    }

    public function copyAddressFrom(PostalAddressInterface $address): static
    {
        $this->postalAddress = $address->getPostalAddress();
        $this->postCode = $address->getPostCode();
        $this->city = $address->getCity();
        $this->country = $address->getCountry();
        // The point belongs to the address it was located from: copied along
        // with it, never kept for another one.
        $this->setGeoPoint($address instanceof GeoLocatedInterface ? $address->getGeoPoint() : null);

        return $this;
    }

    public function hasPostalAddress(): bool
    {
        return ! PostalAddressHelper::isEmpty($this);
    }

    public function toStringInline(): string
    {
        return PostalAddressHelper::toInline($this);
    }
}
