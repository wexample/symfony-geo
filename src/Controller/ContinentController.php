<?php

namespace Wexample\SymfonyGeo\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Attribute\Route;
use Wexample\SymfonyApi\Api\Class\ApiResponse;
use Wexample\SymfonyApi\Api\Controller\AbstractApiController;
use Wexample\SymfonyGeo\Service\ContinentService;

#[Route(path: '_geo/continent/', name: 'geo_continent_')]
class ContinentController extends AbstractApiController
{
    final public const ROUTE_IMPORT = 'import';

    #[Route(path: 'import', name: self::ROUTE_IMPORT, methods: [Request::METHOD_POST, self::ROUTE_OPTION_KEY_EXPOSE => true])]
    public function import(ContinentService $continentService): ApiResponse
    {
        $continentService->seed();

        return self::apiResponseSuccess();
    }
}
