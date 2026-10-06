<?php

namespace Wexample\SymfonyGeo\Helper;

use Wexample\SymfonyGeo\Interface\PostalAddressInterface;

class PostalAddressHelper
{
    /**
     * The address as printed on an envelope or an invoice:
     * street lines, then "post code city", then the country name when asked.
     *
     * @return list<string>
     */
    public static function toLines(
        PostalAddressInterface $address,
        bool $withCountry = true
    ): array {
        $lines = [];

        foreach (preg_split('/\R/', (string) $address->getPostalAddress()) as $line) {
            if ('' !== trim($line)) {
                $lines[] = trim($line);
            }
        }

        $locality = trim($address->getPostCode().' '.$address->getCity());
        if ('' !== $locality) {
            $lines[] = $locality;
        }

        if ($withCountry && $address->getCountry()) {
            $lines[] = $address->getCountry()->getName();
        }

        return $lines;
    }

    public static function toInline(
        PostalAddressInterface $address,
        bool $withCountry = true
    ): string {
        return implode(', ', static::toLines($address, $withCountry));
    }

    public static function isEmpty(PostalAddressInterface $address): bool
    {
        return [] === static::toLines($address);
    }
}
