<?php

namespace App\Genre\UseCases;

use App\Genre\Repositories\IGenreRepository;
use Support\Http\HttpResponse;
use Illuminate\Http\JsonResponse;

class ListAllGenresUseCase {

    public function __construct(private readonly IGenreRepository $genreRepository)
    {
    }

    public function execute(array $filters): JsonResponse
    {
        try {
            $genres = $this->genreRepository->list($filters);

            return HttpResponse::ok($genres);
        } catch (\Exception $e) {
            return HttpResponse::fail($e->getMessage());
        }

    }
}
