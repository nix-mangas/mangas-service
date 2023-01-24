<?php

namespace App\Chapter\UseCases;

use App\Chapter\Repositories\IChapterRepository;
use Support\Http\HttpResponse;
use Illuminate\Http\JsonResponse;

class GetFirstChapterByMangaUseCase {
    public function __construct(private readonly IChapterRepository $chapterRepository)
    {
    }

    public function execute(string $manga): JsonResponse
    {
        try {
            $chapter = $this->chapterRepository->getFirstChapterByManga($manga);

            return HttpResponse::ok([
                'status' => 'success',
                'chapter' => $chapter
            ]);
        } catch (\Exception $e) {
            return HttpResponse::fail($e->getMessage());
        }
    }
}
