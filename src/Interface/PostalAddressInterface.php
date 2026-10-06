<?php

namespace Wexample\SymfonyGeo\Interface;

use Wexample\SymfonyGeo\Entity\Country;

/**
 * A postal address. Its country is a Country entity.
 */
interface PostalAddressInterface
{
    /**
     * Street lines, possibly several separated by line breaks.
     */
    public function getPostalAddress(): ?string;

    public function getPostCode(): ?string;

    public function getCity(): ?string;

    public function getCountry(): ?Country;
}
