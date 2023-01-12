<?php

namespace App\Chapter\UseCases;

use App\Chapter\Repositories\IChapterRepository;
use Support\Http\HttpResponse;
use Illuminate\Http\JsonResponse;

class LatestChaptersUseCase {
    public function __construct(private readonly IChapterRepository $chapterRepository)
    {
    }

    public function execute(array $filters): JsonResponse
    {
        try {
            $result = $this->chapterRepository->latest($filters);

            return HttpResponse::ok($result);
        } catch (\Exception $e) {
            return HttpResponse::fail($e->getMessage());
        }
    }
}
