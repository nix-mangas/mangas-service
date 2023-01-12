<?php

namespace Infra\Http\Api\Controllers\People;

use App\Manga\Requests\People\DeletePeopleRequest;
use App\People\Models\People;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Request;
use Infra\Http\App\Controllers\Controller;

class DestroyPeopleController extends Controller {
    public function __construct(private readonly \App\People\UseCases\DestroyPeopleUseCase $useCase)
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
