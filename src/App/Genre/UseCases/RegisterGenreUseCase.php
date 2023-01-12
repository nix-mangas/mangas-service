<?php

namespace App\Genre\UseCases;

use App\Genre\Repositories\IGenreRepository;
use Support\Http\HttpResponse;
use Illuminate\Http\JsonResponse;

class RegisterGenreUseCase {

    public function __construct(private readonly IGenreRepository $genreRepository)
    {
    }

    public function execute(array $input): JsonResponse
    {
        try {
            $genreAlreadyExists = $this->genreRepository->exists(str($input['name'])->slug('-'));
            if ($genreAlreadyExists) {
                return HttpResponse::conflict('Genre "'.$input['name'].'" already exists!');
            }

            $input['slug'] = $input['name'];

            $this->genreRepository->create($input);

            return HttpResponse::created('Saved genre successfully!');
        } catch (\Exception $e) {
            return HttpResponse::fail($e->getMessage());
        }
    }
}
