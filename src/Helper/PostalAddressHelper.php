<?php

namespace Wexample\SymfonyGeo\Helper;

use Wexample\SymfonyGeo\Interface\PostalAddressInterface;

class PostalAddressHelper
{
    /**
     * The address as printed on an envelope or an invoice:
     * street lines, then "post code city", then the country code when asked.
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

        if ($withCountry && $address->getCountryCode()) {
            $lines[] = $address->getCountryCode();
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

    /**
     * @return array{postalAddress: ?string, postCode: ?string, city: ?string, countryCode: ?string}
     */
    public static function toArray(PostalAddressInterface $address): array
    {
        return [
            'postalAddress' => $address->getPostalAddress(),
            'postCode' => $address->getPostCode(),
            'city' => $address->getCity(),
            'countryCode' => $address->getCountryCode(),
        ];
    }
}
