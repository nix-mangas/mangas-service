<?php

namespace App\Manga\UseCases;

use App\Manga\Repositories\IMangaRepository;
use Support\Http\HttpResponse;
use Illuminate\Http\JsonResponse;

class GetMangaBySlugUseCase {

    public function __construct(private readonly IMangaRepository $mangaRepository)
    {
    }

    public function execute(string $slug): JsonResponse
    {
        try {
            $manga = $this->mangaRepository->findBySlug($slug);

            if (!$manga) {
                return HttpResponse::notFound('Manga not found');
            }

            return HttpResponse::ok([
                'status' => 'success',
                'manga'  => $manga
            ]);
        } catch (\Exception $e) {
            return HttpResponse::fail($e->getMessage());
        }
    }
}
