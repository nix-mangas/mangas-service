<?php

namespace App\Scan\UseCases;

use App\Scan\Models\Scan;
use App\Scan\Repositories\IScanRepository;
use Illuminate\Support\Facades\Storage;
use Support\Http\HttpResponse;
use Illuminate\Http\JsonResponse;

class RegisterScanUseCase {

    public function __construct(private readonly IScanRepository $scanRepository)
    {
    }

    public function execute(array $input): JsonResponse
    {
        try {
            $input['slug'] = str($input['name'])->slug('-');

            $scanAlreadyExists = $this->scanRepository->exists($input['slug']);
            if ($scanAlreadyExists) {
                return HttpResponse::conflict('Scan "'.$input['name'].'" already exists.');
            }

            $folder = 'scans/'.$input['slug'].'/';

            if ($input['logo']) {
                $filename = time().'-logo.'.$input['logo']->getClientOriginalExtension();
                $filepath = 'scans/'.$input['slug'].'/'.$filename;

                Storage::disk('s3')->put($filepath, file_get_contents($input['logo']));

                $input['logo'] = $filepath;
            }

            $this->scanRepository->create($input);

            return HttpResponse::created('Saved scan successfully!');
        } catch (\Exception $e) {
            return HttpResponse::fail($e->getMessage());
        }
    }
}
