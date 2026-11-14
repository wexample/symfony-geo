<?php

declare(strict_types=1);

namespace Wexample\SymfonyGeo\Tests\Unit\Service;

use Doctrine\ORM\EntityManagerInterface;
use PHPUnit\Framework\TestCase;
use Wexample\SymfonyGeo\Data\ContinentData;
use Wexample\SymfonyGeo\Entity\Continent;
use Wexample\SymfonyGeo\Entity\Country;
use Wexample\SymfonyGeo\Repository\ContinentRepository;
use Wexample\SymfonyGeo\Repository\CountryRepository;
use Wexample\SymfonyGeo\Service\ContinentService;
use Wexample\SymfonyGeo\Service\CountryService;
use Wexample\SymfonyMoney\Entity\Currency;
use Wexample\SymfonyMoney\Repository\CurrencyRepository;

/**
 * The seeds read each table once and flush once: no lookup nor flush per row,
 * and the rows already there are updated rather than created twice.
 */
class SeedServicesTest extends TestCase
{
    public function testContinentSeedUpdatesExistingAndCreatesMissingInOneFlush(): void
    {
        $europe = (new Continent())->setIsoAlpha2Code(ContinentData::CODE_EU)->setName('old');

        $saved = [];
        $repository = $this->createPartialMock(ContinentRepository::class, ['findAll', 'save', 'findOneBy']);
        $repository->expects($this->once())->method('findAll')->willReturn([$europe]);
        $repository->expects($this->never())->method('findOneBy');
        $repository->method('save')->willReturnCallback(function (Continent $continent, bool $flush) use (&$saved): void {
            $this->assertFalse($flush);
            $saved[$continent->getIsoAlpha2Code()] = $continent;
        });

        $entityManager = $this->createMock(EntityManagerInterface::class);
        $entityManager->expects($this->once())->method('flush');

        (new ContinentService($repository, $entityManager))->seed();

        $this->assertCount(count(ContinentData::getAll()), $saved);
        $this->assertSame($europe, $saved[ContinentData::CODE_EU]);
        $this->assertSame('europe', $europe->getName());
    }

    public function testCountrySeedResolvesRelationsFromPreloadedTables(): void
    {
        $europe = (new Continent())->setIsoAlpha2Code(ContinentData::CODE_EU)->setName('europe');
        $euro = (new Currency())->setCurrencyCode('EUR');
        $france = (new Country())
            ->setIsoAlpha2Code('FR')
            ->setIsoAlpha3Code('XXX')
            ->setIsoNumericCode('000')
            ->setName('old');

        $continentRepository = $this->createPartialMock(ContinentRepository::class, ['findAll', 'findOneBy']);
        $continentRepository->expects($this->once())->method('findAll')->willReturn([$europe]);
        $continentRepository->expects($this->never())->method('findOneBy');

        $currencyRepository = $this->createPartialMock(CurrencyRepository::class, ['findAll', 'findOneBy']);
        $currencyRepository->expects($this->once())->method('findAll')->willReturn([$euro]);
        $currencyRepository->expects($this->never())->method('findOneBy');

        $saved = [];
        $countryRepository = $this->createPartialMock(CountryRepository::class, ['findAll', 'save', 'findOneBy']);
        $countryRepository->expects($this->once())->method('findAll')->willReturn([$france]);
        $countryRepository->expects($this->never())->method('findOneBy');
        $countryRepository->method('save')->willReturnCallback(function (Country $country, bool $flush) use (&$saved): void {
            $this->assertFalse($flush);
            $saved[$country->getIsoAlpha2Code()] = $country;
        });

        $entityManager = $this->createMock(EntityManagerInterface::class);
        $entityManager->expects($this->once())->method('flush');

        (new CountryService($countryRepository, $continentRepository, $currencyRepository, $entityManager))->seed();

        $this->assertSame($france, $saved['FR']);
        $this->assertSame('FRA', $france->getIsoAlpha3Code());
        $this->assertSame($europe, $france->getContinent());
        $this->assertSame($euro, $france->getCurrency());
        // A country of another continent, created: no continent preloaded for it.
        $this->assertArrayHasKey('JP', $saved);
        $this->assertNull($saved['JP']->getContinent());
    }
}
