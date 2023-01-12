<?php

namespace App\Genre\UseCases;

use App\Genre\Repositories\IGenreRepository;
use Support\Http\HttpResponse;
use Illuminate\Http\JsonResponse;

class DeleteGenreUseCase {

    public function __construct(private readonly IGenreRepository $genreRepository)
    {
    }

    public function execute(string $genre): JsonResponse
    {
        try {
            $this->genreRepository->delete($genre);

            return HttpResponse::ok([ 'message' => 'Deleted genre successfully!' ]);
        } catch (\Exception $e) {
            return HttpResponse::fail($e->getMessage());
        }
    }
}
