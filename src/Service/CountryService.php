<?php

namespace Wexample\SymfonyGeo\Service;

use Rinvex\Country\CountryLoader;
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
        foreach (countries() as $data) {
            $isoAlpha2Code = $data->getIsoAlpha2();
            $isoAlpha3Code = $data->getIsoAlpha3();
            $isoNumericCode = $data->getIsoNumeric();
            $name = strtolower(str_replace([' ', '-', "'"], ['_', '_', ''], $data->getName()));
            $nativeName = $data->getNativeName() ?: null;
            $continentCode = $data->getContinent();
            $currencyCode = $data->getCurrency();

            $continent = $continentCode
                ? $this->continentRepository->findByIsoAlpha2Code($continentCode)
                : null;

            $currency = $currencyCode
                ? $this->currencyRepository->findByCode($currencyCode)
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
