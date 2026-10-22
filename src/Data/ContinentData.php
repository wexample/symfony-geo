<?php

namespace Wexample\SymfonyGeo\Data;

class ContinentData
{
    public const CODE_AF = 'AF';
    public const CODE_AN = 'AN';
    public const CODE_AS = 'AS';
    public const CODE_EU = 'EU';
    public const CODE_NA = 'NA';
    public const CODE_OC = 'OC';
    public const CODE_SA = 'SA';

    public static function getAll(): array
    {
        return [
            self::CODE_AF => ['isoAlpha2Code' => self::CODE_AF, 'name' => 'africa'],
            self::CODE_AN => ['isoAlpha2Code' => self::CODE_AN, 'name' => 'antarctica'],
            self::CODE_AS => ['isoAlpha2Code' => self::CODE_AS, 'name' => 'asia'],
            self::CODE_EU => ['isoAlpha2Code' => self::CODE_EU, 'name' => 'europe'],
            self::CODE_NA => ['isoAlpha2Code' => self::CODE_NA, 'name' => 'north_america'],
            self::CODE_OC => ['isoAlpha2Code' => self::CODE_OC, 'name' => 'oceania'],
            self::CODE_SA => ['isoAlpha2Code' => self::CODE_SA, 'name' => 'south_america'],
        ];
    }
}
