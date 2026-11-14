<?php

namespace Wexample\SymfonyGeo\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Attribute\Route;
use Wexample\SymfonyApi\Api\Class\ApiResponse;
use Wexample\SymfonyApi\Api\Controller\AbstractApiController;
use Wexample\SymfonyGeo\Service\CountryService;

#[Route(path: '_geo/country/', name: 'geo_country_')]
class CountryController extends AbstractApiController
{
    final public const ROUTE_IMPORT = 'import';

    #[Route(path: 'import', name: self::ROUTE_IMPORT, methods: [Request::METHOD_POST], options: self::ROUTE_OPTIONS_ONLY_EXPOSE)]
    public function import(CountryService $countryService): ApiResponse
    {
        $countryService->seed();

        return self::apiResponseSuccess();
    }
}
