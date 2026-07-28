<?php

namespace Wexample\SymfonyGeo\Entity;

use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Wexample\SymfonyGeo\Repository\ContinentRepository;
use Wexample\SymfonyHelpers\Entity\AbstractEntity;
use Wexample\SymfonyHelpers\Entity\Traits\HasNameTrait;
use Wexample\SymfonyHelpers\Entity\Traits\HasSecureIdTrait;

#[ORM\Entity(repositoryClass: ContinentRepository::class)]
#[ORM\Table(name: 'continent')]
class Continent extends AbstractEntity
{
    use HasNameTrait;
    use HasSecureIdTrait;

    #[ORM\Column(type: Types::STRING, length: 2, unique: true)]
    protected string $code;

    public function getCode(): string
    {
        return $this->code;
    }

    public function setCode(string $code): static
    {
        $this->code = $code;

        return $this;
    }
}
