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

        $key = 'mangas-' . $request->query('order') ?? 'default' . $request?->query('per_page') ?? '10';

        if($request->query('search') == null) {
            if (Cache::has($key)) {
                return Cache::get($key);
            }
    
            Cache::put($key, $this->useCase->execute($filters), 60);
            
            return Cache::get($key);
        }

        return $this->useCase->execute($filters);
    }
}