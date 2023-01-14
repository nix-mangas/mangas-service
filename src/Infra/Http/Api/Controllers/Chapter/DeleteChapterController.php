<?php

namespace Infra\Http\Api\Controllers\Chapter;

use App\Chapter\UseCases\DeleteChapterUseCase;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Infra\Http\App\Controllers\Controller;

class DeleteChapterController extends Controller {
    public function __construct(private readonly DeleteChapterUseCase $useCase)
    {
    }

    /**
     * Handle the incoming request.
     *
     * @param Request $request
     * @param string $id
     * @return JsonResponse
     */
    public function __invoke(Request $request, string $id): JsonResponse
    {
        return $this->useCase->execute($id);
    }
}
