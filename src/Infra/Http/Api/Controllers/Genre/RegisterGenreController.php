<?php

namespace Infra\Http\Api\Controllers\Genre;

use App\Genre\UseCases\RegisterGenreUseCase;
use Illuminate\Http\JsonResponse;
use Infra\Http\Api\Requests\RegisterGenreRequest;
use Infra\Http\App\Controllers\Controller;

class RegisterGenreController extends Controller {
    public function __construct(private readonly RegisterGenreUseCase $useCase)
    {
    }

    /**
     * @param RegisterGenreRequest $request
     * @return JsonResponse
     */
    public function __invoke(RegisterGenreRequest $request): JsonResponse
    {
        $request->validated();
        return $this->useCase->execute($request->all());
    }
}
