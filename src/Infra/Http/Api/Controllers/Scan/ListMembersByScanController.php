<?php

namespace Infra\Http\Api\Controllers\Scan;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Infra\Http\App\Controllers\Controller;

class ListMembersByScanController extends Controller {
    public function __construct(private readonly \App\Scan\UseCases\ListMembersByScanUseCase $useCase)
    {
    }

    /**
     * @param Request $request
     * @param string $id
     * @return JsonResponse
     */
    public function __invoke(Request $request, string $id): JsonResponse
    {
        return $this->useCase->execute($id);
    }
}
