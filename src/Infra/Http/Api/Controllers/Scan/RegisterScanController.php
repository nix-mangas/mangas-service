<?php

namespace Infra\Http\Api\Controllers\Scan;

use Illuminate\Http\JsonResponse;
use Infra\Http\Api\Requests\RegisterScanRequest;
use Infra\Http\App\Controllers\Controller;

class RegisterScanController extends Controller {
    public function __construct(private readonly \App\Scan\UseCases\RegisterScanUseCase $useCase)
    {
    }

    /**
     * @param RegisterScanRequest $request
     * @return JsonResponse
     */
    public function __invoke(RegisterScanRequest $request): JsonResponse
    {
        $request->validated();
        return $this->useCase->execute($request->all());
    }
}
