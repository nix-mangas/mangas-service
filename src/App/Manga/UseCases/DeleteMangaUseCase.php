<?php

namespace App\Manga\UseCases;

use App\Manga\Repositories\IMangaRepository;
use Support\Http\HttpResponse;
use Illuminate\Http\JsonResponse;

class DeleteMangaUseCase {

    public function __construct(private readonly IMangaRepository $mangaRepository)
    {
    }

    /**
     * @param string $manga
     * @return JsonResponse
     */
    public function execute(string $manga): JsonResponse
    {
        try {
            $this->mangaRepository->delete($manga);

            return HttpResponse::ok([
                'status'  => 'success',
                'message' => 'Manga deleted successfully!'
            ]);
        } catch (\Exception $e) {
            return HttpResponse::fail($e->getMessage());
        }
    }
}
