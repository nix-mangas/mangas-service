<?php

namespace Infra\Http\Api\Controllers\Manga;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Infra\Http\App\Controllers\Controller;
use Illuminate\Support\Facades\Cache;

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
        if (Cache::has($slug)) {
            return Cache::get($slug);
        }

        Cache::put($slug, $this->useCase->execute($slug), 60);
        
        return Cache::get($slug);
    }
}