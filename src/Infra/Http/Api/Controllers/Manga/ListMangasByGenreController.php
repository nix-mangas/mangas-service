<?php

namespace Infra\Http\Api\Controllers\Manga;


use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Infra\Http\App\Controllers\Controller;
use Illuminate\Support\Facades\Cache;

class ListMangasByGenreController extends Controller {
    public function __construct(private readonly \App\Manga\UseCases\ListMangasByGenreUseCase $useCase)
    {
    }

    /**
     * Handle the incoming request.
     *
     * @param Request $request
     * @param string $genre
     * @return JsonResponse
     */
    public function __invoke(Request $request, string $genre): JsonResponse
    {
        $filters = [
            'order'    => $request?->query('order'),
            'search'   => $request?->query('search'),
            'per_page' => $request?->query('per_page'),
        ];

        return Cache::remember($genre, 60, function ($genre, $filters) {
            return $this->useCase->execute(
                genre  : $genre,
                filters: $filters,
            );
        });
    }
}