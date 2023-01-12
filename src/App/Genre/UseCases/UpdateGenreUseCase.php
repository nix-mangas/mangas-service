<?php

namespace App\Genre\UseCases;

use App\Genre\Repositories\IGenreRepository;
use Support\Http\HttpResponse;
use Illuminate\Http\JsonResponse;

class UpdateGenreUseCase {
    public function __construct(private readonly IGenreRepository $genreRepository)
    {
    }

    public function execute(string $id, array $data): JsonResponse
    {
        try {
            $this->genreRepository->save(id: $id, data: $data);

            return HttpResponse::ok([ 'message' => 'Genre successfully updated!' ]);
        } catch (\Exception $e) {
            return HttpResponse::fail($e->getMessage());
        }
    }
}