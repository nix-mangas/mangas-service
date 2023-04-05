<?php

namespace Infra\Http\Api\Controllers\Chapter;

use App\Chapter\UseCases\LatestChaptersUseCase;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Infra\Http\App\Controllers\Controller;
use Illuminate\Support\Facades\Cache;

class LatestChaptersController extends Controller {
    public function __construct(private readonly LatestChaptersUseCase $useCase)
    {
    }

    /**
     * @param Request $request
     * @return JsonResponse
     */
    public function __invoke(Request $request): JsonResponse
    {
        $filters = $filters = [
            'per_page' => $request?->query('per_page'),
            'page'     => $request?->query('page'),
        ];
        
        Cache::add('latestchapters-' . ($request->query('page') ?? '1') . $request->query('per_page') ?? '10', $this->useCase->execute($filters), 60);

        return Cache::get('latestchapters-' . ($request->query('page') ?? '1') . $request->query('per_page') ?? '10');
    }
}