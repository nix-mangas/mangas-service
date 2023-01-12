<?php

namespace Infra\Http\Api\Controllers\Genre;

use Illuminate\Http\JsonResponse;
use Infra\Http\Api\Requests\UpdateGenreRequest;
use Infra\Http\App\Controllers\Controller;

class UpdateGenreController extends Controller {
    public function __construct(private readonly \App\Genre\UseCases\UpdateGenreUseCase $useCase)
    {
    }

    /**
     * @param UpdateGenreRequest $request
     * @param string $id
     * @return JsonResponse
     */
    public function __invoke(UpdateGenreRequest $request, string $id): JsonResponse
    {
        $request->validated();
        return $this->useCase->execute(id: $id, data: $request->all());
    }
}