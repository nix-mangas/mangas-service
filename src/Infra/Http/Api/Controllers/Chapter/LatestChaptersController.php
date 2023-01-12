<?php

namespace Infra\Http\Api\Controllers\Chapter;

use App\Chapter\UseCases\LatestChaptersUseCase;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Infra\Http\App\Controllers\Controller;

class LatestChaptersController extends Controller {
    public function __construct(private readonly LatestChaptersUseCase $useCase)
    {
    }

    /**
     * @param Request $request
     * @return JsonResponse
     */
    public function __invoke(Request $request): JsonResponse
    {
        $filters = $filters = [
            'per_page' => $request?->query('per_page'),
            'page'     => $request?->query('page'),
        ];
        return $this->useCase->execute($filters);
    }
}
