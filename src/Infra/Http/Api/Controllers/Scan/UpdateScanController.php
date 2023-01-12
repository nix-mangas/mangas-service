<?php

namespace Infra\Http\Api\Controllers\Scan;

use Illuminate\Http\JsonResponse;
use Infra\Http\Api\Requests\UpdateScanRequest;

class UpdateScanController extends \Infra\Http\App\Controllers\Controller {
    public function __construct(private readonly \App\Scan\UseCases\UpdateScanUseCase $useCase)
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