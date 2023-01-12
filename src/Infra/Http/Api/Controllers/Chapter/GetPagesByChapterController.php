<?php

namespace Infra\Http\Api\Controllers\Chapter;

use App\Chapter\UseCases\GetPagesByChapterUseCase;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Infra\Http\App\Controllers\Controller;

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
        return $this->useCase->execute($id);
    }
}
