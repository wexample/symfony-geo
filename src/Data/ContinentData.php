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
            self::CODE_AF => ['code' => self::CODE_AF, 'name' => 'Africa'],
            self::CODE_AN => ['code' => self::CODE_AN, 'name' => 'Antarctica'],
            self::CODE_AS => ['code' => self::CODE_AS, 'name' => 'Asia'],
            self::CODE_EU => ['code' => self::CODE_EU, 'name' => 'Europe'],
            self::CODE_NA => ['code' => self::CODE_NA, 'name' => 'North America'],
            self::CODE_OC => ['code' => self::CODE_OC, 'name' => 'Oceania'],
            self::CODE_SA => ['code' => self::CODE_SA, 'name' => 'South America'],
        ];
    }
}
