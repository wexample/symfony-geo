<?php

namespace Wexample\SymfonyGeo\Class;

use Wexample\SymfonyGeo\Helper\PostalAddressHelper;
use Wexample\SymfonyGeo\Interface\PostalAddressInterface;

/**
 * An immutable address, for snapshots: the address an invoice was sent to,
 * the billing address of a paid cart. Stored as JSON through toArray().
 */
final readonly class PostalAddress implements PostalAddressInterface
{
    public function __construct(
        public ?string $postalAddress = null,
        public ?string $postCode = null,
        public ?string $city = null,
        public ?string $countryCode = null,
    ) {
    }

    public static function fromAddress(PostalAddressInterface $address): self
    {
        return new self(
            $address->getPostalAddress(),
            $address->getPostCode(),
            $address->getCity(),
            $address->getCountryCode(),
        );
    }

    public static function fromArray(array $data): self
    {
        return new self(
            $data['postalAddress'] ?? null,
            $data['postCode'] ?? null,
            $data['city'] ?? null,
            $data['countryCode'] ?? null,
        );
    }

    public function toArray(): array
    {
        return PostalAddressHelper::toArray($this);
    }

    public function getPostalAddress(): ?string
    {
        return $this->postalAddress;
    }

    public function getPostCode(): ?string
    {
        return $this->postCode;
    }

    public function getCity(): ?string
    {
        return $this->city;
    }

    public function getCountryCode(): ?string
    {
        return $this->countryCode;
    }
}
