<?php

namespace App\Chapter\UseCases;

use App\Chapter\Repositories\IChapterRepository;
use Illuminate\Http\JsonResponse;
use Support\Http\HttpResponse;

class GetChapterDetailsUseCase {
    public function __construct(private readonly IChapterRepository $chapterRepository)
    {
    }

    public function execute(string $manga, string $chapter): JsonResponse
    {
        try {
            $result = $this->chapterRepository->getByMangaAndNumber(manga: $manga, number: $chapter);

            if (!$result) {
                return HttpResponse::notFound('Chapter not found!');
            }

            return HttpResponse::ok([
                'status'  => 'success',
                'chapter' => $result['chapter'],
                'next'    => $result['next'],
                'prev'    => $result['prev'],
            ]);
        } catch (\Exception $e) {
            return HttpResponse::fail($e->getMessage());
        }
    }
}
