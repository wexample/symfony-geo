<?php

namespace Wexample\SymfonyGeo\Service;

use Doctrine\ORM\EntityManagerInterface;
use Wexample\SymfonyGeo\Data\ContinentData;
use Wexample\SymfonyGeo\Repository\ContinentRepository;

class ContinentService
{
    public function __construct(
        private readonly ContinentRepository $continentRepository,
        private readonly EntityManagerInterface $entityManager,
    ) {
    }

    public function seed(): void
    {
        // The table read once, keyed by its unique code, and written in one
        // flush: a lookup and a flush per continent otherwise.
        $continents = [];

        foreach ($this->continentRepository->findAll() as $continent) {
            $continents[$continent->getIsoAlpha2Code()] = $continent;
        }

        foreach (ContinentData::getAll() as $data) {
            $continent = $continents[$data['isoAlpha2Code']] ?? null;

            if ($continent) {
                $continent->setName($data['name']);
            } else {
                $continent = $this->continentRepository->createNewContinent(
                    isoAlpha2Code: $data['isoAlpha2Code'],
                    name: $data['name'],
                );
                $continents[$data['isoAlpha2Code']] = $continent;
            }

            $this->continentRepository->save($continent, flush: false);
        }

        $this->entityManager->flush();
    }
}
