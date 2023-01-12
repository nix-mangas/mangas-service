<?php

namespace Infra\Http\Api\Controllers\Genre;

use App\Genre\Policies\GenrePolicy;
use App\Genre\UseCases\DestroyGenreUseCase;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Infra\Http\App\Controllers\Controller;

class DestroyGenreController extends Controller {
    public function __construct(private readonly DestroyGenreUseCase $useCase)
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
