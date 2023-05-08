<?php

namespace Infra\Http\Api\Controllers\Manga;

use App\Manga\Models\Manga;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Infra\Http\App\Controllers\Controller;
use Illuminate\Support\Facades\Cache;

class GetMangaBySlugController extends Controller
{
    public function __construct(private readonly \App\Manga\UseCases\GetMangaBySlugUseCase $useCase)
    {
    }

    /**
     * Handle the incoming request.
     *
     * @param Request $request
     * @param string $slug
     * @return JsonResponse
     */
    public function __invoke(Request $request, string $slug): JsonResponse
    {
        $manga = Cache::remember(
            'mangas::'.$slug,
            60,
            function () use ($request, $slug) {
                return Manga::query()
                    ->with(['firstChapter', 'lastChapter', 'genres'])
                    ->withCount(['chapters'])
                    ->where('id', $slug)
                    ->orWhere('slug', $slug)
                    ->firstOrFail();
            }
        );

        return response()->json([
            'manga' => $manga,
        ]);
    }
}
