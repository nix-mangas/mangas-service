<?php

namespace App\Manga\UseCases;

use App\Manga\Repositories\IMangaRepository;
use Support\Http\HttpResponse;
use Illuminate\Http\JsonResponse;

class ListMangasByGenreUseCase {

    public function __construct(private readonly IMangaRepository $mangaRepository)
    {
    }

    public function execute(string $genre, array $filters): JsonResponse
    {
        try {
            $result = $this->mangaRepository->listByGenre(
                genre  : $genre,
                filters: $filters
            );

            return HttpResponse::ok($result);
        } catch (\Exception $e) {
            return HttpResponse::fail($e->getMessage());
        }
    }
}
