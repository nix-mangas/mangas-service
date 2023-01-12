<?php

namespace App\People\UseCases;

use App\People\Repositories\IPeopleRepository;
use Support\Http\HttpResponse;
use Illuminate\Http\JsonResponse;

class ListWorksByPeopleUseCase {

    public function __construct(private readonly IPeopleRepository $peopleRepository)
    {
    }

    public function execute(string $people, array $filters): JsonResponse
    {
        try {
            $result = $this->peopleRepository->listAllWorksByPeople(
                people : $people,
                filters: $filters
            );

            if (!$result) {
                return HttpResponse::notFound('People not found');
            }


            return HttpResponse::ok($result);
        } catch (\Exception $e) {
            return HttpResponse::fail($e->getMessage());
        }
    }
}
