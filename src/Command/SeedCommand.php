<?php

namespace Wexample\SymfonyGeo\Command;

use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Wexample\SymfonyGeo\Repository\ContinentRepository;
use Wexample\SymfonyGeo\Repository\CountryRepository;
use Wexample\SymfonyGeo\Service\ContinentService;
use Wexample\SymfonyGeo\Service\CountryService;
use Wexample\SymfonyGeo\WexampleSymfonyGeoBundle;
use Wexample\SymfonyHelpers\Command\AbstractBundleCommand;
use Wexample\SymfonyHelpers\Service\BundleService;

/**
 * The continents and the countries of the `rinvex/countries` dataset, written
 * once and updated after: what `_geo/continent/import` and
 * `_geo/country/import` do, for an application with no one to click them.
 *
 * A country takes the currency of its dataset entry when that currency is
 * already in the table, so `money:seed` comes first.
 */
class SeedCommand extends AbstractBundleCommand
{
    public function __construct(
        BundleService $bundleService,
        private readonly ContinentService $continentService,
        private readonly CountryService $countryService,
        private readonly ContinentRepository $continentRepository,
        private readonly CountryRepository $countryRepository,
    ) {
        parent::__construct($bundleService);
    }

    public static function getBundleClassName(): string
    {
        return WexampleSymfonyGeoBundle::class;
    }

    protected function configure(): void
    {
        $this->setDescription('Writes the continents and countries of the reference dataset; those already there are updated.');
    }

    protected function execute(
        InputInterface $input,
        OutputInterface $output
    ): int {
        $this->continentService->seed();
        $this->countryService->seed();

        $output->writeln(sprintf(
            '%d continents, %d countries.',
            $this->continentRepository->count([]),
            $this->countryRepository->count([]),
        ));

        return self::SUCCESS;
    }
}
