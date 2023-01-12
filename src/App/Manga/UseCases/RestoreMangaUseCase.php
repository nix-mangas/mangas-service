<?php

namespace App\Manga\UseCases;

use App\Manga\Repositories\IMangaRepository;
use Support\Http\HttpResponse;
use Illuminate\Http\JsonResponse;

class RestoreMangaUseCase {

    public function __construct(private readonly IMangaRepository $mangaRepository)
    {
    }

    public function execute(string $manga): JsonResponse
    {
        try {
            $this->mangaRepository->restore($manga);

            return HttpResponse::ok([
                'status'  => 'success',
                'message' => 'Manga restored successfully!'
            ]);
        } catch (\Exception $e) {
            return HttpResponse::fail($e->getMessage());
        }
    }
}
