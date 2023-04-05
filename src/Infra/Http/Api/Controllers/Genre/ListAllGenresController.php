<?php

namespace Infra\Http\Api\Controllers\Genre;

use App\Genre\UseCases\ListAllGenresUseCase;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Infra\Http\App\Controllers\Controller;
use Illuminate\Support\Facades\Cache;

class ListAllGenresController extends Controller {
    public function __construct(private readonly ListAllGenresUseCase $useCase)
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

        $key = 'genres-' . ($request->query('page') ?? '1') . $request?->query('per_page') ?? '10';

        if($request?->query('search') == null) {
            if (Cache::has($key)) {
                return Cache::get($key);
            }
    
            Cache::put($key, $this->useCase->execute($filters), 90);
            
            return Cache::get($key);
        }

        return $this->useCase->execute($filters);
    }
}