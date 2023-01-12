<?php

namespace App\People\UseCases;

use App\People\Repositories\IPeopleRepository;
use Support\Http\HttpResponse;
use Illuminate\Http\JsonResponse;

class UpdatePeopleUseCase {
    public function __construct(private readonly IPeopleRepository $peopleRepository)
    {
    }

    public function execute(string $id, array $input): JsonResponse
    {
        try {
            $this->peopleRepository->save($id, $input);

            return HttpResponse::created('People successfully updated!');
        } catch (\Exception $e) {
            return HttpResponse::fail($e->getMessage());
        }
    }
}
