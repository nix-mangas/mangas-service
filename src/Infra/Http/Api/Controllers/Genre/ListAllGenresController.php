<?php

namespace Infra\Http\Api\Controllers\Genre;

use App\Genre\UseCases\ListAllGenresUseCase;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Infra\Http\App\Controllers\Controller;

class ListAllGenresController extends Controller {
    public function __construct(private readonly ListAllGenresUseCase $useCase)
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
