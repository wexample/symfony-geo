<?php

namespace Wexample\SymfonyGeo\Service;

use Doctrine\ORM\EntityManagerInterface;
use Wexample\SymfonyGeo\Repository\ContinentRepository;
use Wexample\SymfonyGeo\Repository\CountryRepository;
use Wexample\SymfonyMoney\Repository\CurrencyRepository;

class CountryService
{
    public function __construct(
        private readonly CountryRepository $countryRepository,
        private readonly ContinentRepository $continentRepository,
        private readonly CurrencyRepository $currencyRepository,
        private readonly EntityManagerInterface $entityManager,
    ) {
    }

    public function seed(): void
    {
        // The three tables read once, keyed by the code each row is looked up
        // by (all three unique): a query per country per table was the cost of
        // a seed of some 250 countries.
        $continents = $this->indexBy($this->continentRepository->findAll(), static fn ($continent) => $continent->getIsoAlpha2Code());
        $currencies = $this->indexBy($this->currencyRepository->findAll(), static fn ($currency) => $currency->getCurrencyCode());
        $countries = $this->indexBy($this->countryRepository->findAll(), static fn ($country) => $country->getIsoAlpha2Code());

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

            $continent = $continentCode ? ($continents[$continentCode] ?? null) : null;
            $currency = $currencyCode ? ($currencies[$currencyCode] ?? null) : null;

            $country = $countries[$isoAlpha2Code] ?? null;

            if ($country) {
                $country
                    ->setIsoAlpha3Code($isoAlpha3Code)
                    ->setIsoNumericCode($isoNumericCode)
                    ->setName($name)
                    ->setNativeName($nativeName)
                    ->setContinent($continent)
                    ->setCurrency($currency);
            } else {
                $country = $this->countryRepository->createNewCountry(
                    isoAlpha2Code: $isoAlpha2Code,
                    isoAlpha3Code: $isoAlpha3Code,
                    isoNumericCode: $isoNumericCode,
                    name: $name,
                    nativeName: $nativeName,
                    continent: $continent,
                    currency: $currency,
                );
                $countries[$isoAlpha2Code] = $country;
            }

            $this->countryRepository->save($country, flush: false);
        }

        // One flush for the whole dataset.
        $this->entityManager->flush();
    }

    /**
     * @template T
     * @param T[] $entities
     * @return array<string, T>
     */
    private function indexBy(
        array $entities,
        callable $key
    ): array {
        $indexed = [];

        foreach ($entities as $entity) {
            $indexed[$key($entity)] = $entity;
        }

        return $indexed;
    }
}
