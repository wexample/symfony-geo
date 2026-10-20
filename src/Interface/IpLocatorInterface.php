<?php

namespace Wexample\SymfonyGeo\Interface;

/**
 * Where an IP address stands, as far as a country: what an audience counts
 * its visitors by, without keeping the address itself. Implemented by a
 * package holding the data (symfony-remote-db-ip); absent, nothing is located.
 */
interface IpLocatorInterface
{
    /**
     * The ISO 3166-1 alpha-2 code, upper case; null for an address the data
     * does not place — a private one, an unknown one, data not downloaded yet.
     */
    public function locateCountry(string $ip): ?string;
}
