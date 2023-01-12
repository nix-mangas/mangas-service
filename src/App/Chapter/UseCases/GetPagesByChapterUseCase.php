<?php

namespace App\Chapter\UseCases;

use App\Chapter\Repositories\IChapterRepository;
use Support\Http\HttpResponse;
use Illuminate\Http\JsonResponse;

class GetPagesByChapterUseCase {
    public function __construct(private readonly IChapterRepository $chapterRepository)
    {
    }

    public function execute(string $chapter): JsonResponse
    {
        try {
            $result = $this->chapterRepository->listPagesByChapter($chapter);

            if (!$result) {
                return HttpResponse::notFound('Chapter not found!');
            }

            return HttpResponse::ok([
                'status' => 'success',
                'pages'  => $result,
            ]);
        } catch (\Exception $e) {
            return HttpResponse::fail($e->getMessage());
        }
    }
}
