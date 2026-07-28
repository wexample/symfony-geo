<?php

namespace Wexample\SymfonyGeo\Entity\Traits\Manipulator;

use Wexample\SymfonyGeo\Entity\Country;
use Wexample\SymfonyHelpers\Entity\Traits\Manipulator\EntityManipulatorTrait;

trait CountryEntityManipulatorTrait
{
    use EntityManipulatorTrait;

    public static function getEntityClassName(): string
    {
        return Country::class;
    }
}
