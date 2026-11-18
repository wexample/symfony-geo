<?php

namespace Wexample\SymfonyGeo\Controller;

use Wexample\SymfonyHelpers\Helper\RoleHelper;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Attribute\Route;
use Wexample\SymfonyApi\Api\Class\ApiResponse;
use Wexample\SymfonyApi\Api\Controller\AbstractApiController;
use Wexample\SymfonyGeo\Service\ContinentService;

#[Route(path: '_geo/continent/', name: 'geo_continent_')]
class ContinentController extends AbstractApiController
{
    final public const ROUTE_IMPORT = 'import';

    // Reseeds the shared reference data: an administrator's.
    #[Route(path: 'import', name: self::ROUTE_IMPORT, methods: [Request::METHOD_POST], options: self::ROUTE_OPTIONS_ONLY_EXPOSE)]
    #[IsGranted(RoleHelper::ROLE_ADMIN)]
    public function import(ContinentService $continentService): ApiResponse
    {
        $continentService->seed();

        return self::apiResponseSuccess();
    }
}
