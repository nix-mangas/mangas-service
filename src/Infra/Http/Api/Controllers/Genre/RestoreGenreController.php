<?php

namespace Infra\Http\Api\Controllers\Genre;

use App\Genre\UseCases\RestoreGenreUseCase;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Infra\Http\App\Controllers\Controller;


class RestoreGenreController extends Controller {
    public function __construct(private readonly RestoreGenreUseCase $useCase)
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
