<?php

namespace App\Genre\UseCases;

use App\Genre\Repositories\IGenreRepository;
use Support\Http\HttpResponse;
use Illuminate\Http\JsonResponse;

class RestoreGenreUseCase {

    public function __construct(private readonly IGenreRepository $genreRepository)
    {
    }

    public function execute(string $genre): JsonResponse
    {
        try {
            $this->genreRepository->restore($genre);

            return HttpResponse::ok([ 'message' => 'Genre successfully restored!' ]);
        } catch (\Exception $e) {
            return HttpResponse::fail($e->getMessage());
        }
    }
}
