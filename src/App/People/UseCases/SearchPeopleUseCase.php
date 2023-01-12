<?php

namespace App\People\UseCases;

use App\People\Repositories\IPeopleRepository;
use Support\Http\HttpResponse;
use Illuminate\Http\JsonResponse;

class SearchPeopleUseCase {

    public function __construct(private readonly IPeopleRepository $peopleRepository)
    {
    }

    public function execute(array $filters): JsonResponse
    {
        try {
            $result = $this->peopleRepository->list($filters);

            return HttpResponse::ok($result);
        } catch (\Exception $e) {
            return HttpResponse::fail($e->getMessage());
        }
    }
}
