<?php

namespace Wexample\SymfonyGeo\Entity\Traits\Manipulator;

use Wexample\SymfonyGeo\Entity\Continent;
use Wexample\SymfonyHelpers\Entity\Traits\Manipulator\EntityManipulatorTrait;

trait ContinentEntityManipulatorTrait
{
    use EntityManipulatorTrait;

    public static function getEntityClassName(): string
    {
        return Continent::class;
    }
}
