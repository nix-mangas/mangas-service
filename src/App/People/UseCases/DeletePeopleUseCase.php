<?php

namespace App\People\UseCases;

use App\People\Repositories\IPeopleRepository;
use Illuminate\Http\JsonResponse;
use Support\Http\HttpResponse;

class DeletePeopleUseCase {

    public function __construct(private readonly IPeopleRepository $peopleRepository)
    {
    }

    public function execute(string $people): JsonResponse
    {
        try {
            $this->peopleRepository->delete($people);

            return HttpResponse::ok([
                'status'  => 'success',
                'message' => 'People deleted successfully!'
            ]);
        } catch (\Exception $e) {
            return HttpResponse::fail($e->getMessage());
        }
    }
}
