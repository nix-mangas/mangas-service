<?php

namespace Infra\Http\Api\Controllers\People;


use App\Manga\Requests\People\DeletePeopleRequest;
use App\People\Models\People;
use App\People\UseCases\RestorePeopleUseCase;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Request;
use Infra\Http\App\Controllers\Controller;

class RestorePeopleController extends Controller {
    public function __construct(private readonly RestorePeopleUseCase $useCase)
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
