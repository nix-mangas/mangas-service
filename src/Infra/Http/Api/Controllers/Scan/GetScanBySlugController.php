<?php

namespace Infra\Http\Api\Controllers\Scan;

use App\Scan\UseCases\GetScanBySlugUseCase;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Infra\Http\App\Controllers\Controller;

class GetScanBySlugController extends Controller {
    public function __construct(private readonly GetScanBySlugUseCase $useCase)
    {
    }

    /**
     * @param Request $request
     * @param string $slug
     * @return JsonResponse
     */
    public function __invoke(Request $request, string $slug): JsonResponse
    {
        return $this->useCase->execute($slug);
    }
}
