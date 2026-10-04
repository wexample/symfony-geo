<?php

namespace Wexample\SymfonyGeo\Interface;

/**
 * A postal address. The country is an ISO 3166-1 alpha-2 code ("FR", "BE"),
 * the key of the Country entity, so an address can be copied or snapshotted
 * without a database.
 */
interface PostalAddressInterface
{
    /**
     * Street lines, possibly several separated by line breaks.
     */
    public function getPostalAddress(): ?string;

    public function getPostCode(): ?string;

    public function getCity(): ?string;

    public function getCountryCode(): ?string;
}
