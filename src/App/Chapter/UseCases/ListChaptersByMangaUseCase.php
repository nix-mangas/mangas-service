<?php

namespace App\Chapter\UseCases;

use App\Chapter\Repositories\IChapterRepository;
use Support\Http\HttpResponse;
use Illuminate\Http\JsonResponse;

class ListChaptersByMangaUseCase {
    public function __construct(private readonly IChapterRepository $chapterRepository)
    {
    }

    public function execute(string $manga, array $filters): JsonResponse
    {
        try {
            $result = $this->chapterRepository->listByManga($manga, $filters);

            return HttpResponse::ok($result);
        } catch (\Exception $e) {
            return HttpResponse::fail($e->getMessage());
        }
    }
}
