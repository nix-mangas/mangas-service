<?php

namespace Infra\Http\Api\Controllers\Chapter;

use App\Chapter\UseCases\RestoreChapterUseCase;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Infra\Http\App\Controllers\Controller;

class RestoreChapterController extends Controller {
    public function __construct(private readonly RestoreChapterUseCase $useCase)
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
