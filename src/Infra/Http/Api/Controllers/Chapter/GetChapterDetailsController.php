<?php

namespace Infra\Http\Api\Controllers\Chapter;

use App\Chapter\UseCases\GetChapterDetailsUseCase;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Infra\Http\App\Controllers\Controller;
use Illuminate\Support\Facades\Cache;

class GetChapterDetailsController extends Controller {
    public function __construct(private readonly GetChapterDetailsUseCase $useCase)
    {
    }

    /**
     * @param Request $request
     * @param string $slug
     * @param string $number
     * @return JsonResponse
     */
    public function __invoke(Request $request, string $slug, string $number): JsonResponse
    {
        Cache::add($slug . '-' . $number, $this->useCase->execute(manga: $slug, chapter: $number), 60);

        return Cache::get($slug . '-' . $number);
    }
}