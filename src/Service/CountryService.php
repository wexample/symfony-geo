<?php

namespace Wexample\SymfonyGeo\Service;

use Wexample\SymfonyGeo\Repository\ContinentRepository;
use Wexample\SymfonyGeo\Repository\CountryRepository;
use Wexample\SymfonyMoney\Repository\CurrencyRepository;

class CountryService
{
    public function __construct(
        private readonly CountryRepository $countryRepository,
        private readonly ContinentRepository $continentRepository,
        private readonly CurrencyRepository $currencyRepository,
    ) {
    }

    public function seed(): void
    {
        // Every country, long-listed and hydrated: the short list as arrays
        // holds none of the accessors read below.
        foreach (countries(true, true) as $data) {
            $isoAlpha2Code = $data->getIsoAlpha2();
            $isoAlpha3Code = $data->getIsoAlpha3();
            $isoNumericCode = $data->getIsoNumeric();
            $name = strtolower(str_replace([' ', '-', "'"], ['_', '_', ''], $data->getName()));
            $nativeName = $data->getNativeName() ?: null;
            // Its continent by code: the library gives its name, keyed by it.
            $continentCode = array_key_first($data->getAttributes()['geo']['continent'] ?? []);
            $currencyCode = $data->getCurrency()['iso_4217_code'] ?? null;

            $continent = $continentCode
                ? $this->continentRepository->findByIsoAlpha2Code($continentCode)
                : null;

            $currency = $currencyCode
                ? $this->currencyRepository->findByCurrencyCode($currencyCode)
                : null;

            $country = $this->countryRepository->findByIsoAlpha2Code($isoAlpha2Code);

            if ($country) {
                $country
                    ->setIsoAlpha3Code($isoAlpha3Code)
                    ->setIsoNumericCode($isoNumericCode)
                    ->setName($name)
                    ->setNativeName($nativeName)
                    ->setContinent($continent)
                    ->setCurrency($currency);

                $this->countryRepository->save($country);
            } else {
                $this->countryRepository->saveNewCountry(
                    isoAlpha2Code: $isoAlpha2Code,
                    isoAlpha3Code: $isoAlpha3Code,
                    isoNumericCode: $isoNumericCode,
                    name: $name,
                    nativeName: $nativeName,
                    continent: $continent,
                    currency: $currency,
                );
            }
        }
    }
}
