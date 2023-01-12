<?php

namespace Infra\Http\Api\Controllers\Genre;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Infra\Http\App\Controllers\Controller;

class ListAllGenresController extends Controller {
    public function __construct(private readonly \App\Genre\UseCases\ListAllGenresUseCase $useCase)
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
