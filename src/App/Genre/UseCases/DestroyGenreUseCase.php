<?php

namespace App\Genre\UseCases;

use App\Genre\Repositories\IGenreRepository;
use Support\Http\HttpResponse;
use Exception;
use Illuminate\Http\JsonResponse;

class DestroyGenreUseCase {

    public function __construct(private readonly IGenreRepository $genreRepository)
    {
    }

    public function execute(string $genre): JsonResponse
    {
        try {
            $this->genreRepository->destroy($genre);

            return HttpResponse::ok([ 'message' => 'Destroyed genre successfully!' ]);
        } catch (Exception $e) {
            return HttpResponse::fail($e->getMessage());
        }
    }
}
