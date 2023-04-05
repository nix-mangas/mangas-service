<?php

namespace Infra\Http\Api\Controllers\Manga;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Infra\Http\App\Controllers\Controller;
use Illuminate\Support\Facades\Cache;

class SearchMangaController extends Controller {
    public function __construct(private readonly \App\Manga\UseCases\SearchMangaUseCase $useCase)
    {
    }

    /**
     * @param Request $request
     * @return JsonResponse
     */
    public function __invoke(Request $request): JsonResponse
    {
        $filters = [
            'order'    => $request?->query('order'),
            'search'   => $request?->query('search'),
            'per_page' => $request?->query('per_page'),
        ];

        if($request->query('search') == null) {
            Cache::add('mangas-' . $request->query('order') ?? 'default' . $request?->query('per_page') ?? '10', $this->useCase->execute($filters), 10);

            return Cache::get('mangas-' . $request->query('order') ?? 'default' . $request?->query('per_page') ?? '10');
        }

        return $this->useCase->execute($filters);
    }
}