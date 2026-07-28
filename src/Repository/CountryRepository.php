<?php

namespace Wexample\SymfonyGeo\Repository;

use Wexample\SymfonyGeo\Entity\Continent;
use Wexample\SymfonyGeo\Entity\Country;
use Wexample\SymfonyGeo\Entity\Traits\Manipulator\CountryEntityManipulatorTrait;
use Wexample\SymfonyHelpers\Repository\AbstractRepository;
use Wexample\SymfonyMoney\Entity\Currency;

/**
 * @method Country|null find($id, $lockMode = null, $lockVersion = null)
 * @method Country|null findOneBy(array $criteria, array $orderBy = null)
 * @method Country|null findOneByIsoAlpha2Code(string $isoAlpha2Code)
 * @method Country|null findOneByIsoAlpha3Code(string $isoAlpha3Code)
 * @method Country|null saveNewCountry(string $isoAlpha2Code, string $isoAlpha3Code, string $isoNumericCode, string $name, ?string $nativeName, ?Continent $continent, ?Currency $currency)
 * @method Country[]    findAll()
 * @method Country[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class CountryRepository extends AbstractRepository
{
    use CountryEntityManipulatorTrait;

    public function createNewCountry(
        string $isoAlpha2Code,
        string $isoAlpha3Code,
        string $isoNumericCode,
        string $name,
        ?string $nativeName,
        ?Continent $continent,
        ?Currency $currency,
    ): Country {
        $country = new Country();
        $country
            ->setIsoAlpha2Code($isoAlpha2Code)
            ->setIsoAlpha3Code($isoAlpha3Code)
            ->setIsoNumericCode($isoNumericCode)
            ->setName($name)
            ->setNativeName($nativeName)
            ->setContinent($continent)
            ->setCurrency($currency)
            ->setGeneratedSecureId();

        return $country;
    }

    public function findByIsoAlpha2Code(string $isoAlpha2Code): ?Country
    {
        return $this->findOneBy(['isoAlpha2Code' => $isoAlpha2Code]);
    }
}
