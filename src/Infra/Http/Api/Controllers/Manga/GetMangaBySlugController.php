<?php

namespace Infra\Http\Api\Controllers\Manga;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Infra\Http\App\Controllers\Controller;

class GetMangaBySlugController extends Controller {
    public function __construct(private readonly \App\Manga\UseCases\GetMangaBySlugUseCase $useCase)
    {
    }

    /**
     * Handle the incoming request.
     *
     * @param Request $request
     * @param string $slug
     * @return JsonResponse
     */
    public function __invoke(Request $request, string $slug): JsonResponse
    {
        return $this->useCase->execute($slug);
    }
}
