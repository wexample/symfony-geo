<?php

namespace Wexample\SymfonyGeo\Entity;

use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Wexample\SymfonyGeo\Repository\CountryRepository;
use Wexample\SymfonyHelpers\Entity\AbstractEntity;
use Wexample\SymfonyHelpers\Entity\Traits\HasNameTrait;
use Wexample\SymfonyMoney\Entity\Currency;

#[ORM\Entity(repositoryClass: CountryRepository::class)]
#[ORM\Table(name: 'country')]
class Country extends AbstractEntity
{
    use HasNameTrait;

    #[ORM\Column(type: Types::STRING, length: 2, unique: true)]
    protected string $isoAlpha2Code;

    #[ORM\Column(type: Types::STRING, length: 3, unique: true)]
    protected string $isoAlpha3Code;

    #[ORM\Column(type: Types::STRING, length: 3, unique: true)]
    protected string $isoNumericCode;

    #[ORM\Column(type: Types::STRING, length: 255, nullable: true)]
    protected ?string $nativeName = null;

    #[ORM\ManyToOne(targetEntity: Continent::class)]
    #[ORM\JoinColumn(nullable: true)]
    protected ?Continent $continent = null;

    #[ORM\ManyToOne(targetEntity: Currency::class)]
    #[ORM\JoinColumn(nullable: true)]
    protected ?Currency $currency = null;

    public function getIsoAlpha2Code(): string
    {
        return $this->isoAlpha2Code;
    }

    public function setIsoAlpha2Code(string $isoAlpha2Code): static
    {
        $this->isoAlpha2Code = $isoAlpha2Code;

        return $this;
    }

    public function getIsoAlpha3Code(): string
    {
        return $this->isoAlpha3Code;
    }

    public function setIsoAlpha3Code(string $isoAlpha3Code): static
    {
        $this->isoAlpha3Code = $isoAlpha3Code;

        return $this;
    }

    public function getIsoNumericCode(): string
    {
        return $this->isoNumericCode;
    }

    public function setIsoNumericCode(string $isoNumericCode): static
    {
        $this->isoNumericCode = $isoNumericCode;

        return $this;
    }

    public function getNativeName(): ?string
    {
        return $this->nativeName;
    }

    public function setNativeName(?string $nativeName): static
    {
        $this->nativeName = $nativeName;

        return $this;
    }

    public function getContinent(): ?Continent
    {
        return $this->continent;
    }

    public function setContinent(?Continent $continent): static
    {
        $this->continent = $continent;

        return $this;
    }

    public function getCurrency(): ?Currency
    {
        return $this->currency;
    }

    public function setCurrency(?Currency $currency): static
    {
        $this->currency = $currency;

        return $this;
    }
}
