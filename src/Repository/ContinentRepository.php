<?php

namespace Wexample\SymfonyGeo\Repository;

use Wexample\SymfonyGeo\Entity\Continent;
use Wexample\SymfonyGeo\Entity\Traits\Manipulator\ContinentEntityManipulatorTrait;
use Wexample\SymfonyHelpers\Repository\AbstractRepository;

/**
 * @method Continent|null find($id, $lockMode = null, $lockVersion = null)
 * @method Continent|null findOneBy(array $criteria, array $orderBy = null)
 * @method Continent|null findOneByCode(string $code)
 * @method Continent|null saveNewContinent(string $code, string $name)
 * @method Continent[]    findAll()
 * @method Continent[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ContinentRepository extends AbstractRepository
{
    use ContinentEntityManipulatorTrait;

    public function createNewContinent(
        string $code,
        string $name,
    ): Continent {
        $continent = new Continent();
        $continent
            ->setCode($code)
            ->setName($name)
            ->setGeneratedSecureId();

        return $continent;
    }

    public function findByCode(string $code): ?Continent
    {
        return $this->findOneBy(['code' => $code]);
    }
}
