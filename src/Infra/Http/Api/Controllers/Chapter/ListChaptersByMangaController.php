<?php

namespace Infra\Http\Api\Controllers\Chapter;

use App\Chapter\UseCases\ListChaptersByMangaUseCase;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Infra\Http\App\Controllers\Controller;
use Illuminate\Support\Facades\Cache;

class ListChaptersByMangaController extends Controller {
    public function __construct(private readonly ListChaptersByMangaUseCase $useCase)
    {
    }

    public function __invoke(Request $request, string $slug): JsonResponse
    {
        $filters = [
            'order'    => $request?->query('sort_by'),
            'search'   => $request?->query('search'),
            'per_page' => $request?->query('per_page'),
        ];

        $key = 'mangachapters' . $slug . ($request?->query('sort_by') ?? 'default') .  $request?->query('per_page') ?? '30';

        if($request?->query('search') == null) {
            if (Cache::has($key)) {
                return Cache::get($key);
            }
    
            Cache::put($key, $this->useCase->execute($slug, $filters), 60);
            
            return Cache::get($key);
        }

        return $this->useCase->execute($slug, $filters);
    }
}