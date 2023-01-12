<?php

namespace Infra\Http\Api\Controllers\People;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Infra\Http\App\Controllers\Controller;

class SearchPeopleController extends Controller {
    public function __construct(private readonly \App\People\UseCases\SearchPeopleUseCase $useCase)
    {
    }

    /**
     * Handle the incoming request.
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function __invoke(Request $request): JsonResponse
    {
        $filters = [
            'order'    => $request?->query('order'),
            'search'   => $request?->query('search'),
            'per_page' => $request?->query('per_page')
        ];
        return $this->useCase->execute($filters);
    }
}
