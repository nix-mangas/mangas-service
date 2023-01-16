<?php

namespace Infra\Http\Api\Controllers\Scan;

use App\Scan\Models\Scan;
use App\Scan\UseCases\RestoreScanUseCase;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Infra\Http\App\Controllers\Controller;

class RestoreScanController extends Controller {
    public function __construct(private readonly RestoreScanUseCase $useCase)
    {
    }

    public function __invoke(Request $request, string $id): JsonResponse
    {
        return $this->useCase->execute($id);
    }
}
