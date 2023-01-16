<?php

namespace Infra\Http\Api\Controllers\Scan;

use App\Scan\UseCases\RegisterScanUseCase;
use Illuminate\Http\JsonResponse;
use Infra\Http\Api\Requests\RegisterScanRequest;
use Infra\Http\App\Controllers\Controller;

class RegisterScanController extends Controller {
    public function __construct(private readonly RegisterScanUseCase $useCase)
    {
    }

    /**
     * @param RegisterScanRequest $request
     * @return JsonResponse
     */
    public function __invoke(RegisterScanRequest $request): JsonResponse
    {
        $request->validated();
        return $this->useCase->execute(array_merge($request->all(), [ 'logo' => $request->file('logo')]));
    }
}
