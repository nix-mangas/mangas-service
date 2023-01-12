<?php

namespace Infra\Http\Api\Controllers\People;

use App\People\UseCases\UpdatePeopleUseCase;
use Illuminate\Http\JsonResponse;
use Infra\Http\Api\Requests\UpdatePeopleRequest;
use Infra\Http\App\Controllers\Controller;

class UpdatePeopleController extends Controller {
    public function __construct(private readonly UpdatePeopleUseCase $useCase)
    {
    }

    /**
     * @param UpdatePeopleRequest $request
     * @param string $id
     * @return JsonResponse
     */
    public function __invoke(UpdatePeopleRequest $request, string $id): JsonResponse
    {
        $request->validated();
        return $this->useCase->execute(id: $id, input: $request->all());
    }
}