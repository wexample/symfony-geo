<?php

namespace Wexample\SymfonyGeo\Entity;

use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Stringable;
use Wexample\SymfonyGeo\Entity\Traits\HasPostalAddressTrait;
use Wexample\SymfonyGeo\Interface\PostalAddressInterface;
use Wexample\SymfonyHelpers\Entity\AbstractEntity;

/**
 * A postal address with an optional addressee, shared by organizations, users
 * and carts. Owners (user, organization) are mapped by the extending entity.
 */
#[ORM\MappedSuperclass]
abstract class AbstractAddress extends AbstractEntity implements PostalAddressInterface, Stringable
{
    use HasPostalAddressTrait;

    #[ORM\Column(type: Types::STRING, length: 255, nullable: true)]
    protected ?string $firstName = null;

    #[ORM\Column(type: Types::STRING, length: 255, nullable: true)]
    protected ?string $lastName = null;

    public function __toString(): string
    {
        return $this->toStringInline();
    }

    public function getFirstName(): ?string
    {
        return $this->firstName;
    }

    public function setFirstName(?string $firstName): static
    {
        $this->firstName = $firstName;

        return $this;
    }

    public function getLastName(): ?string
    {
        return $this->lastName;
    }

    public function setLastName(?string $lastName): static
    {
        $this->lastName = $lastName;

        return $this;
    }

    public function getFullName(): ?string
    {
        $name = trim($this->firstName.' '.$this->lastName);

        return '' === $name ? null : $name;
    }

    /**
     * Copies location and addressee, never the identity or the owner.
     */
    public function copyFrom(self $address): static
    {
        $this->copyAddressFrom($address);
        $this->firstName = $address->getFirstName();
        $this->lastName = $address->getLastName();

        return $this;
    }
}
