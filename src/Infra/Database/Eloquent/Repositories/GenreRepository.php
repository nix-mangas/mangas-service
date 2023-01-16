<?php

namespace Infra\Database\Eloquent\Repositories;

use App\Genre\Models\Genre;
use App\Genre\Repositories\IGenreRepository;
use Illuminate\Support\Facades\Storage;

class GenreRepository implements IGenreRepository {
    public function list(array $filters)
    {
        $orderBy = $filters['order'] ?? 'asc';
        $search  = $filters['search'] ?? null;
        $perPage = $filters['per_page'] ?? 30;

        if ($search) {
            return Genre::search($search)->orderBy('name', $orderBy)->paginate($perPage);
        }

        return Genre::orderBy('name', $orderBy)->paginate($perPage);
    }

    public function exists(string $slug): bool
    {
        $genre = Genre::withTrashed()->firstWhere('slug', $slug);
        return (bool)$genre;
    }

    public function create(array $data): void
    {
        Genre::create($data);
    }

    public function save(string $id, array $data): void
    {
        Genre::find($id)?->update($data);
    }

    public function delete(string $id): void
    {
        Genre::destroy($id);
    }

    public function destroy(string $id): void
    {
        $genre = Genre::withTrashed()->find($id);
        $genre?->forceDelete();
    }

    public function restore(string $id): void
    {
        Genre::withTrashed()->find($id)?->restore();
    }
}
