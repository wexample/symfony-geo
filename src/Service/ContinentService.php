<?php

namespace Wexample\SymfonyGeo\Service;

use Wexample\SymfonyGeo\Data\ContinentData;
use Wexample\SymfonyGeo\Repository\ContinentRepository;

class ContinentService
{
    public function __construct(
        private readonly ContinentRepository $continentRepository,
    ) {
    }

    public function seed(): void
    {
        foreach (ContinentData::getAll() as $data) {
            $continent = $this->continentRepository->findByIsoAlpha2Code($data['isoAlpha2Code']);

            if ($continent) {
                $continent->setName($data['name']);
                $this->continentRepository->save($continent);
            } else {
                $this->continentRepository->saveNewContinent(
                    isoAlpha2Code: $data['isoAlpha2Code'],
                    name: $data['name'],
                );
            }
        }
    }
}
