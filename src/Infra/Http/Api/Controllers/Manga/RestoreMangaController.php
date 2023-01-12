<?php

namespace Infra\Http\Api\Controllers\Manga;

use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Infra\Http\App\Controllers\Controller;

class RestoreMangaController extends Controller {
    public function __construct(private readonly \App\Manga\UseCases\RestoreMangaUseCase $useCase)
    {
    }

    /**
     * @param Request $request
     * @param string $id
     * @return JsonResponse
     */
    public function __invoke(Request $request, string $id): JsonResponse
    {
        return $this->useCase->execute($id);
    }
}
