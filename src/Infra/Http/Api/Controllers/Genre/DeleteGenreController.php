<?php

namespace Infra\Http\Api\Controllers\Genre;

use App\Genre\UseCases\DeleteGenreUseCase;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Infra\Http\App\Controllers\Controller;

class DeleteGenreController extends Controller {
    public function __construct(private readonly DeleteGenreUseCase $useCase)
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
        return $this->useCase->execute($id);
    }
}
