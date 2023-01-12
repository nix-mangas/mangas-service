<?php

namespace Infra\Http\Api\Controllers\Scan;

use App\Scan\Models\Scan;
use App\Scan\UseCases\DeleteScanUseCase;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Infra\Http\App\Controllers\Controller;

class DeleteScanController extends Controller {
    public function __construct(private readonly DeleteScanUseCase $useCase)
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
