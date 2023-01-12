<?php

namespace Infra\Http\Api\Controllers\Scan;

use App\Scan\UseCases\ListAllScanUseCase;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Infra\Http\App\Controllers\Controller;

class SearchScanController extends Controller {
    public function __construct(private readonly ListAllScanUseCase $useCase)
    {
    }

    /**
     * @param Request $request
     * @return JsonResponse
     */
    public function __invoke(Request $request): JsonResponse
    {
        $filters = [
            'order'    => $request?->query('order'),
            'search'   => $request?->query('search'),
            'per_page' => $request?->query('per_page'),
        ];
        return $this->useCase->execute($filters);
    }
}
