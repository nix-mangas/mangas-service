<?php

namespace Infra\Http\Api\Controllers\Scan;


use App\Scan\UseCases\ListMangasByScanUseCase;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Infra\Http\App\Controllers\Controller;

class ListMangasByScanController extends Controller {
    public function __construct(private readonly ListMangasByScanUseCase $useCase)
    {
    }

    /**
     * @param Request $request
     * @param string $id
     * @return JsonResponse
     */
    public function __invoke(Request $request, string $id): JsonResponse
    {
        return $this->useCase->execute($id);
    }
}
