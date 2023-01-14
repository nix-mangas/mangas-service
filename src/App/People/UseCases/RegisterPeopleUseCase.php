<?php

namespace App\People\UseCases;

use App\People\Repositories\IPeopleRepository;
use Illuminate\Support\Facades\Storage;
use Support\Http\HttpResponse;
use Illuminate\Http\JsonResponse;

class RegisterPeopleUseCase {

    public function __construct(private readonly IPeopleRepository $peopleRepository)
    {
    }

    public function execute(array $input): JsonResponse
    {
        try {
            $input['slug'] = str($input['name'])->slug('-');

            if ($input['photo']) {
                $filename = time().'-photo.'.$input['photo']->getClientOriginalExtension();
                $filepath = 'peoples/'.$input['slug'].'/'.$filename;

                Storage::disk('s3')->put($filepath, file_get_contents($input['photo']));

                $input['photo'] = $filepath;
            }

            $this->peopleRepository->create($input);

            return HttpResponse::ok([
                'status'  => 'success',
                'message' => 'People successfully saved!',
            ]);
        } catch (\Exception $e) {
            return HttpResponse::fail($e->getMessage());
        }
    }
}
