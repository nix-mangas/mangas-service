<?php

namespace Infra\Http\Api\Controllers\Chapter;

use App\Chapter\UseCases\GetPagesByChapterUseCase;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Infra\Http\App\Controllers\Controller;
use Illuminate\Support\Facades\Cache;

class GetPagesByChapterController extends Controller {
    public function __construct(private readonly GetPagesByChapterUseCase $useCase)
    {
    }

    /**
     * @param Request $request
     * @param string $id
     * @return JsonResponse
     */
    public function __invoke(Request $request, string $id): JsonResponse
    {
        if (Cache::has($id)) {
            return Cache::get($id);
        }

        Cache::put($id, $this->useCase->execute($id), 60);
        
        return Cache::get($id);
    }
}