<?php

namespace Wexample\SymfonyGeo\Entity\Traits\Manipulator;

use Wexample\SymfonyGeo\Entity\Address;
use Wexample\SymfonyHelpers\Entity\Traits\Manipulator\EntityManipulatorTrait;

trait AddressEntityManipulatorTrait
{
    use EntityManipulatorTrait;

    public static function getEntityClassName(): string
    {
        return Address::class;
    }
}
