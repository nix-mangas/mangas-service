<?php

namespace App\Chapter\UseCases;

use App\Chapter\Repositories\IChapterRepository;
use Support\Http\HttpResponse;
use Illuminate\Http\JsonResponse;

class DeleteChapterUseCase {

    public function __construct(private readonly IChapterRepository $chapterRepository)
    {
    }

    public function execute(string $chapter): JsonResponse
    {
        try {
            $this->chapterRepository->delete($chapter);

            return HttpResponse::ok([
                'status'  => 'success',
                'message' => 'Chapter deleted successfully!'
            ]);
        } catch (\Exception $e) {
            return HttpResponse::fail($e->getMessage());
        }
    }
}
