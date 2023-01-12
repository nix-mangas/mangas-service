<?php

namespace Infra\Http\Api\Controllers\Manga;

use App\Manga\UseCases\RegisterMangaUseCase;
use Illuminate\Http\JsonResponse;
use Infra\Http\Api\Requests\RegisterMangaRequest;
use Infra\Http\App\Controllers\Controller;

class RegisterMangaController extends Controller {
    public function __construct(private readonly RegisterMangaUseCase $useCase)
    {
    }

    /**
     * @param RegisterMangaRequest $request
     * @return JsonResponse
     */
    public function __invoke(RegisterMangaRequest $request): JsonResponse
    {
        $request->validated();

        return $this->useCase->execute(
            array_merge(
                $request->all(),
                [
                    'cover'     => $request->file('cover'),
                    'thumbnail' => $request->file('thumbnail')
                ]
            )
        );
    }
}
