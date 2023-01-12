<?php

namespace Infra\Http\Api\Controllers\Scan;


use App\Scan\UseCases\AssociateMangaToScanUseCase;
use Illuminate\Http\JsonResponse;
use Infra\Http\Api\Requests\AssociateMangaToRequest;
use Infra\Http\App\Controllers\Controller;

class AssociateMangaToScanController extends Controller {
    public function __construct(private readonly AssociateMangaToScanUseCase $useCase)
    {
    }

    /**
     * Handle the incoming request.
     *
     * @param AssociateMangaToRequest $request
     * @param string $slug
     * @return JsonResponse
     */
    public function __invoke(AssociateMangaToRequest $request, string $slug): JsonResponse
    {
        $request->validated();
        return $this->useCase->execute(
            slug  : $slug,
            mangas: $request['mangas']
        );
    }
}
