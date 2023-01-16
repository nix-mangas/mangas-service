<?php

namespace Infra\Database\Eloquent\Repositories;

use App\Manga\Models\Manga;
use App\Manga\Repositories\IMangaRepository;
use Illuminate\Support\Facades\Storage;

class MangaRepository implements IMangaRepository {
    public function findById(string $id): ?Manga
    {
        $manga = Manga::find($id);
        if (!$manga) {
            return null;
        }

        return $manga;
    }

    public function findBySlug(string $slug)
    {
        $manga = Manga::where('slug', $slug)?->with([ 'chapters', 'genres', 'staff' ])->first();
        if (!$manga) {
            return null;
        }

        return $manga;
    }

    public function exists(string $slug): bool
    {
        $manga = Manga::withTrashed()->firstWhere('slug', $slug);
        return (bool)$manga;
    }

    public function create(array $data): void
    {
        $manga = new Manga();
        $manga->fill($data);
        $manga->save();

        if (!empty($data['genres'])) {
            $manga->genres()->sync($data['genres']);
        }

        if (!empty($data['staff'])) {
            $manga->staff()->sync($data['staff']);
        }
    }

    public function save(string $id, array $data): void
    {
        $manga = Manga::find($id);

        if ($data['title']) {
            $manga['slug'] = $data['title'];
        }

        if (!empty($data['genres'])) {
            $manga->genres()->sync($data['genres']);
        }

        if (!empty($data['staff'])) {
            $manga->staff()->sync($data['staff']);
        }

        $manga->fill($data);
        $manga->save();
    }

    public function delete(string $id): void
    {
        Manga::destroy($id);
    }

    public function destroy(string $id): void
    {
        $manga = Manga::withTrashed()->find($id);
        $manga?->forceDelete();
    }

    public function restore(string $id): void
    {
        Manga::withTrashed()->find($id)?->restore();
    }

    public function list(array $filters)
    {
        $orderBy = $filters['order'] ?? 'desc';
        $perPage = $filters['per_page'] ?? 30;
        $search  = $filters['search'] ?? null;


        if ($search) {
            return Manga::search($search)->query(fn ($query) => $query->orderBy('created_at', $orderBy))->paginate($perPage);
        }

        return Manga::orderBy('created_at', $orderBy)->paginate($perPage);
    }

    public function listByGenre(string $genre, array $filters)
    {
        $orderBy = $filters['order'] ?? 'asc';
        $perPage = $filters['per_page'] ?? 30;
        $search  = $filters['search'];

        if ($search) {
            return Manga::search($search)
                        ->query(fn ($query) => $query->whereRelation('genres', 'slug', $genre)->orderBy('created_at', $orderBy))
                        ->paginate($perPage);
        }

        return Manga::whereRelation('genres', 'slug', $genre)
                    ->orderBy('created_at', $orderBy)
                    ->paginate($perPage);
    }
}
