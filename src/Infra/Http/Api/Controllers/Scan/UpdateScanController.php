<?php

namespace Infra\Http\Api\Controllers\Scan;

use App\Scan\UseCases\UpdateScanUseCase;
use Illuminate\Http\JsonResponse;
use Infra\Http\Api\Requests\UpdateScanRequest;
use Infra\Http\App\Controllers\Controller;

class UpdateScanController extends Controller {
    public function __construct(private readonly UpdateScanUseCase $useCase)
    {
    }

    /**
     * @param UpdateScanRequest $request
     * @param string $id
     * @return JsonResponse
     */
    public function __invoke(UpdateScanRequest $request, string $id): JsonResponse
    {
        $request->validated();
        return $this->useCase->execute(id: $id, data: $request->all());
    }
}
