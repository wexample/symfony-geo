<?php

namespace Wexample\SymfonyGeo\Entity\Traits;

use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping\Column;
use Wexample\SymfonyGeo\Helper\PostalAddressHelper;
use Wexample\SymfonyGeo\Interface\PostalAddressInterface;

/**
 * Postal address columns for any entity. Implements PostalAddressInterface.
 */
trait HasPostalAddressTrait
{
    #[Column(type: Types::STRING, length: 255, nullable: true)]
    protected ?string $postalAddress = null;

    #[Column(type: Types::STRING, length: 60, nullable: true)]
    protected ?string $postCode = null;

    #[Column(type: Types::STRING, length: 255, nullable: true)]
    protected ?string $city = null;

    #[Column(type: Types::STRING, length: 2, nullable: true)]
    protected ?string $countryCode = null;

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

    public function getCountryCode(): ?string
    {
        return $this->countryCode;
    }

    public function setCountryCode(?string $countryCode): static
    {
        $this->countryCode = null === $countryCode ? null : strtoupper($countryCode);

        return $this;
    }

    public function copyAddressFrom(PostalAddressInterface $address): static
    {
        $this->postalAddress = $address->getPostalAddress();
        $this->postCode = $address->getPostCode();
        $this->city = $address->getCity();
        $this->countryCode = $address->getCountryCode();

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
