<?php

namespace Infra\Http\Api\Controllers\People;

use App\People\UseCases\RegisterPeopleUseCase;
use Illuminate\Http\JsonResponse;
use Infra\Http\Api\Requests\RegisterPeopleRequest;
use Infra\Http\App\Controllers\Controller;

class RegisterPeopleController extends Controller {
    public function __construct(private readonly RegisterPeopleUseCase $useCase)
    {
    }

    /**
     * @param RegisterPeopleRequest $request
     * @return JsonResponse
     */
    public function __invoke(RegisterPeopleRequest $request): JsonResponse
    {
        $request->validated();
        return $this->useCase->execute($request->all());
    }
}
