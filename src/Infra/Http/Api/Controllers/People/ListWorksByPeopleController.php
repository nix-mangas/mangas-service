<?php

namespace Infra\Http\Api\Controllers\People;

use App\People\UseCases\ListWorksByPeopleUseCase;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Infra\Http\App\Controllers\Controller;

class ListWorksByPeopleController extends Controller {
    public function __construct(private readonly ListWorksByPeopleUseCase $useCase)
    {
    }

    /**
     * Handle the incoming request.
     *
     * @param Request $request
     * @param string $id
     * @return JsonResponse
     */
    public function __invoke(Request $request, string $id): JsonResponse
    {
        $filters = [
            'order'    => $request?->query('order'),
            'search'   => $request?->query('search'),
            'per_page' => $request?->query('per_page'),
        ];
        return $this->useCase->execute(
            people : $id,
            filters: $filters
        );
    }
}
