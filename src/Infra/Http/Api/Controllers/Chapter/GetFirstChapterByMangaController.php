<?php

namespace Infra\Http\Api\Controllers\Chapter;

use App\Chapter\UseCases\GetFirstChapterByMangaUseCase;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Infra\Http\App\Controllers\Controller;

class GetFirstChapterByMangaController extends Controller {
    public function __construct(private readonly GetFirstChapterByMangaUseCase $useCase)
    {
    }

    public function __invoke(Request $request, string $slug): JsonResponse
    {
        return $this->useCase->execute($slug);
    }
}
