<?php

namespace Wexample\SymfonyGeo\Entity;

use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Stringable;
use Wexample\SymfonyGeo\Interface\PostalAddressInterface;

/**
 * An address as it was written at one moment — on an order, an invoice, a
 * shipment —, kept in the columns of what it is written on (an embeddable),
 * and never changed after.
 *
 * Its country is held as text, its ISO code and its name as they were, and
 * not as a relation: a document keeps what was printed on it, whatever
 * becomes of the countries' table. That is the one place an ISO code stands
 * outside it.
 */
#[ORM\Embeddable]
class AddressSnapshot implements Stringable
{
    #[ORM\Column(type: Types::STRING, length: 255, nullable: true)]
    private ?string $firstName = null;

    #[ORM\Column(type: Types::STRING, length: 255, nullable: true)]
    private ?string $lastName = null;

    #[ORM\Column(type: Types::STRING, length: 255, nullable: true)]
    private ?string $postalAddress = null;

    #[ORM\Column(type: Types::STRING, length: 60, nullable: true)]
    private ?string $postCode = null;

    #[ORM\Column(type: Types::STRING, length: 255, nullable: true)]
    private ?string $city = null;

    #[ORM\Column(type: Types::STRING, length: 2, nullable: true)]
    private ?string $countryCode = null;

    #[ORM\Column(type: Types::STRING, length: 255, nullable: true)]
    private ?string $countryName = null;

    /**
     * The address `$address` reads now, its addressee with it when it has one.
     */
    public static function fromAddress(PostalAddressInterface $address): self
    {
        $snapshot = new self();
        $snapshot->postalAddress = $address->getPostalAddress();
        $snapshot->postCode = $address->getPostCode();
        $snapshot->city = $address->getCity();
        $snapshot->countryCode = $address->getCountry()?->getIsoAlpha2Code();
        $snapshot->countryName = $address->getCountry()?->getName();

        if ($address instanceof AbstractAddress) {
            $snapshot->firstName = $address->getFirstName();
            $snapshot->lastName = $address->getLastName();
        }

        return $snapshot;
    }

    public function getFirstName(): ?string
    {
        return $this->firstName;
    }

    public function getLastName(): ?string
    {
        return $this->lastName;
    }

    public function getFullName(): ?string
    {
        $name = trim($this->firstName.' '.$this->lastName);

        return '' === $name ? null : $name;
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

    public function getCountryName(): ?string
    {
        return $this->countryName;
    }

    /**
     * As printed: the addressee, the street lines, "post code city", the
     * country.
     *
     * @return list<string>
     */
    public function toLines(bool $withCountry = true): array
    {
        $lines = array_values(array_filter(
            array_map('trim', preg_split('/\R/', (string) $this->postalAddress)),
            static fn (string $line) => '' !== $line
        ));

        $locality = trim($this->postCode.' '.$this->city);

        if ('' !== $locality) {
            $lines[] = $locality;
        }

        if ($withCountry && $this->countryName) {
            $lines[] = $this->countryName;
        }

        if ($name = $this->getFullName()) {
            array_unshift($lines, $name);
        }

        return $lines;
    }

    public function isEmpty(): bool
    {
        return [] === $this->toLines();
    }

    public function __toString(): string
    {
        return implode(', ', $this->toLines());
    }
}
