<?php

namespace Wexample\SymfonyGeo\Repository;

use Wexample\SymfonyGeo\Entity\Continent;
use Wexample\SymfonyGeo\Entity\Traits\Manipulator\ContinentEntityManipulatorTrait;
use Wexample\SymfonyHelpers\Repository\AbstractRepository;

/**
 * @method Continent|null find($id, $lockMode = null, $lockVersion = null)
 * @method Continent|null findOneBy(array $criteria, array $orderBy = null)
 * @method Continent|null findOneByIsoAlpha2Code(string $isoAlpha2Code)
 * @method Continent|null saveNewContinent(string $isoAlpha2Code, string $name)
 * @method Continent[]    findAll()
 * @method Continent[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ContinentRepository extends AbstractRepository
{
    use ContinentEntityManipulatorTrait;

    public function createNewContinent(
        string $isoAlpha2Code,
        string $name,
    ): Continent {
        $continent = new Continent();
        $continent
            ->setIsoAlpha2Code($isoAlpha2Code)
            ->setName($name)
            ->setGeneratedSecureId();

        return $continent;
    }

    public function findByIsoAlpha2Code(string $isoAlpha2Code): ?Continent
    {
        return $this->findOneBy(['isoAlpha2Code' => $isoAlpha2Code]);
    }
}
