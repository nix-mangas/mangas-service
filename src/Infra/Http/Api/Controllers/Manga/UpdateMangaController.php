<?php

namespace Infra\Http\Api\Controllers\Manga;

use App\Manga\UseCases\UpdateMangaUseCase;
use Illuminate\Http\JsonResponse;
use Infra\Http\Api\Requests\UpdateMangaRequest;
use Infra\Http\App\Controllers\Controller;

class UpdateMangaController extends Controller {
    public function __construct(private readonly UpdateMangaUseCase $useCase)
    {
    }

    /**
     * @param UpdateMangaRequest $request
     * @param string $id
     * @return JsonResponse
     */
    public function __invoke(UpdateMangaRequest $request, string $id): JsonResponse
    {
        $request->validated();

        return $this->useCase->execute(
            id   : $id,
            input: array_merge(
                $request->all(),
                [
                    'cover'     => $request->file('cover'),
                    'thumbnail' => $request->file('thumbnail')
                ]
            )
        );
    }
}