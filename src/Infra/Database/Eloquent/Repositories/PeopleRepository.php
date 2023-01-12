<?php

namespace Infra\Database\Eloquent\Repositories;

use App\People\Models\People;
use App\People\Repositories\IPeopleRepository;
use Illuminate\Support\Facades\Storage;

class PeopleRepository implements IPeopleRepository {

    public function list(array $filters)
    {
        $orderBy = $filters['order'] ?? 'asc';
        $perPage = $filters['per_page'] ?? 30;
        $search  = $filters['search'] ?? null;

        if ($search) {
            return People::search($search)->paginate($perPage);
        }

        return People::orderBy('name', $orderBy)->paginate($perPage);
    }

    public function listAllWorksByPeople(string $people, array $filters)
    {
        $orderBy = $filters['order'] ?? 'asc';
        $perPage = $filters['per_page'] ?? 30;

        $people = People::find($people);
        return $people?->mangas()->orderBy('title', $orderBy)->paginate($perPage);
    }

    public function exists(string $slug)
    {
        $people = People::withTrashed()->firstWhere('slug', $slug);
        return (bool)$people;
    }

    public function create(array $data): void
    {
        People::create($data);
    }

    public function save(string $id, array $data)
    {
        People::find($id)?->update($data);
    }

    public function delete(string $id)
    {
        People::destroy($id);
    }

    public function destroy(string $id)
    {
        $people = People::withTrashed()->find($id);

        if ((bool)$people && $people['photo'] && Storage::disk('s3')->exists($people['photo'])) {
            Storage::disk('s3')->delete($people['photo']);
        }

        $people?->forceDelete();
    }

    public function restore(string $id)
    {
        People::withTrashed()->find($id)?->restore();
    }
}
