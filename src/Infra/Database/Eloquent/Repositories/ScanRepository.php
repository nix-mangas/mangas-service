<?php

namespace Infra\Database\Eloquent\Repositories;

use App\Scan\Models\Scan;
use App\Scan\Repositories\IScanRepository;
use Illuminate\Support\Facades\Storage;

class ScanRepository implements IScanRepository {
    public function list(array $filters)
    {
        $orderBy = $filters['order'] ?? 'desc';
        $perPage = $filters['per_page'] ?? 30;
        $search  = $filters['search'] ?? null;

        if ($search) {
            return Scan::search($search)->query(fn ($query) => $query->orderBy('created_at', $orderBy))->paginate($perPage);
        }

        return Scan::orderBy('created_at', $orderBy)->paginate($perPage);
    }

    public function getAllMangasByScan(string $scan)
    {
        $scan = Scan::find($scan);
        return $scan?->mangas()->paginate(30);
    }

    public function getAllMembersByScan(string $scan)
    {
        $scan = Scan::find($scan);
        return $scan?->members()->paginate(30);
    }

    public function findById(string $id)
    {
        $scan = Scan::find($id);
        if (!$scan) {
            return null;
        }

        return $scan;
    }

    public function findBySlug(string $slug)
    {
        $scan = Scan::where('slug', $slug)?->with([ 'members', 'mangas', 'owner' ])->first();
        if (!$scan) {
            return null;
        }

        return $scan;
    }

    public function exists(string $slug): bool
    {
        $scan = Scan::withTrashed()->firstWhere('slug', $slug);
        return (bool)$scan;
    }

    public function create(array $data): void
    {
        Scan::create($data);
    }

    public function save(string $id, array $data): void
    {
        Scan::find($id)?->update($data);
    }

    public function delete(string $id): void
    {
        Scan::destroy($id);
    }

    public function destroy(string $id): void
    {
        $scan = Scan::withTrashed()->find($id);

        if ((bool)$scan && $scan['logo'] &&   Storage::disk('s3')->exists($scan['logo'])) {
            Storage::disk('s3')->delete($scan['logo']);
        }

        $scan?->forceDelete();
    }

    public function restore(string $id): void
    {
        Scan::withTrashed()->find($id)?->restore();
    }
}
