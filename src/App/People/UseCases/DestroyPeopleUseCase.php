<?php

namespace App\People\UseCases;

use App\People\Repositories\IPeopleRepository;
use Support\Http\HttpResponse;
use Illuminate\Http\JsonResponse;

class DestroyPeopleUseCase {

    public function __construct(private readonly IPeopleRepository $peopleRepository)
    {
    }

    public function execute(string $people): JsonResponse
    {
        try {
            $this->peopleRepository->destroy($people);

            return HttpResponse::ok([
                'status'  => 'success',
                'message' => 'People deleted successfully!'
            ]);
        } catch (\Exception $e) {
            return HttpResponse::fail($e->getMessage());
        }
    }
}
