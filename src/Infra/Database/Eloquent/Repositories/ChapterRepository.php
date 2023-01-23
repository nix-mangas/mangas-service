<?php

namespace Infra\Database\Eloquent\Repositories;

use App\Chapter\Models\Chapter;
use App\Chapter\Repositories\IChapterRepository;
use App\Manga\Models\Manga;
use Illuminate\Support\Facades\Storage;

class ChapterRepository implements IChapterRepository {

    public function latestEagerLoading(array $filters)
    {
        $perPage = $filters['per_page'] ?? 30;

        return Manga::withWhereHas('chapters', function ($query) {
            $query->where('published_plus_at', '<=', now())
                  ->where('published_plus_at', '>=', now()->subDays(3))
                  ->orderBy('published_plus_at', 'desc');
        })
                    ->orderBy('last_published_at', 'desc')
                    ->paginate($perPage);
    }

    public function latest(array $filters): array
    {
        $pageFilter   = $filters['page'] ?? 1;
        $perPageInput = $filters['per_page'] ?? 10;

        $page    = (int)$pageFilter;
        $perPage = (int)$perPageInput;
        $skip    = (int)(($page - 1) * $perPage);

        $count  = Manga::whereRelation('chapters', 'published_plus_at', '<=', now())->count();
        $mangas = Manga::whereHas('chapters', function ($query) {
            $query->where('published_plus_at', '<=', now())->orderBy('published_plus_at', 'desc');
        })->orderBy('last_published_at', 'desc')
                       ->skip($skip)
                       ->take($perPage)
                       ->get();

        foreach ($mangas as $key => $manga) {
            $chapters = $manga->chapters()
                              ->where('published_plus_at', '<=', now())
                              ->where('published_plus_at', '>=', now()->subDays(3))
                              ->orderBy('published_plus_at', 'desc')
                              ->limit(5)
                              ->get();

            $mangas[$key]['chapters'] = $chapters;
        }

        $totalPages = ceil($count / $perPage);
        $toNextPage = $page + 1 > $totalPages ? null : $page + 1;

        return [
            'current_page' => $page,
            'data'         => $mangas,
            "per_page"     => $perPage,
            "to"           => $toNextPage,
            "total"        => $totalPages,
            "last_page"    => $totalPages
        ];
    }

    public function listLatestFromVip(array $filters): array
    {
        $pageFilter   = $filters['page'] ?? 1;
        $perPageInput = $filters['per_page'] ?? 10;

        $page    = (int)$pageFilter;
        $perPage = (int)$perPageInput;
        $skip    = (int)(($page - 1) * $perPage);

        $count  = Manga::whereRelation('chapters', 'published_plus_at', '<=', now())->count();
        $mangas = Manga::whereHas('chapters', function ($query) {
            $query->where('published_plus_at', '<=', now())->orderBy('published_plus_at', 'desc');
        })->orderBy('last_published_at', 'desc')
                       ->skip($skip)
                       ->take($perPage)
                       ->get();

        foreach ($mangas as $key => $manga) {
            $chapters = $manga->chapters()
                              ->where('published_plus_at', '<=', now())
                              ->orderBy('published_plus_at', 'desc')
                              ->limit(5)
                              ->get();

            $mangas[$key]['chapters'] = $chapters;
        }

        $totalPages = ceil($count / $perPage);
        $toNextPage = $page + 1 > $totalPages ? null : $page + 1;

        return [
            'current_page' => $page,
            'data'         => $mangas,
            "per_page"     => $perPage,
            "to"           => $toNextPage,
            "total"        => $totalPages,
            "last_page"    => $totalPages
        ];
    }

    public function listByManga(string $manga, array $filters)
    {
        $orderBy = $filters['order'] ?? 'desc';
        $perPage = $filters['per_page'] ?? 30;
        $search  = $filters['search'];

        if ($search) {
            return Chapter::search($search)->query(fn ($query) =>
                $query->whereRelation('manga', 'slug', $manga)
                      ->where('published_at', '<=', now())
                      ->with([ 'scan' ])
                      ->orderBy('number', 'DESC')
            )->paginate($perPage);
        }

        return Chapter::whereRelation('manga', 'slug', $manga)
                      ->where('published_at', '<=', now())
                      ->with([ 'scan' ])
                      ->orderByDesc('number')
                      ->paginate($perPage);
    }

    public function exists(string $manga, string $number): bool
    {
        $chapter = Chapter::withTrashed()
                          ->whereRelation('manga', 'manga_id', '=', $manga)
                          ->firstWhere('number', $number);
        return (bool)$chapter;
    }

    public function save(string $id, array $data): void
    {
        Chapter::find($id)?->update($data);
    }

    public function savePages(string $id, array $data): void
    {
        Chapter::find($id)?->pages()->createMany($data);
    }

    public function create(array $data): Chapter
    {
        return Chapter::create($data);
    }

    public function delete(string $id): void
    {
        Chapter::destroy($id);
    }

    public function destroy(string $id): void
    {
        $chapter = Chapter::withTrashed()->find($id);
        $chapter?->forceDelete();
    }

    public function restore(string $id): void
    {
        Chapter::withTrashed()->find($id)?->restore();
    }

    public function findById(string $id)
    {
        $chapter = Chapter::where('is_published', true)
                          ->with([ 'pages', 'scan' ])
                          ->firstWhere('id', $id);
        if (!$chapter) {
            return null;
        }

        return $chapter;
    }

    public function listPagesByChapter(string $id)
    {
        $chapter = Chapter::find($id);
        if (!$chapter) {
            return null;
        }

        return $chapter->pages()->get();
    }

    public function getByMangaAndNumber(string $manga, string $number)
    {
        $prev = Chapter::whereRelation('manga', 'slug', $manga)
                       ->orderBy('number', 'desc')
                       ->where('published_at', '<=', now())
                       ->firstWhere('number', '<', $number);

        $next = Chapter::whereRelation('manga', 'slug', $manga)
                       ->where('published_at', '<=', now())
                       ->firstWhere('number', '>', $number);

        $chapter = Chapter::with([ 'pages' ])
                          ->whereRelation('manga', 'slug', $manga)
                          ->firstWhere('number', $number);

        return [
            'prev'    => $prev,
            'next'    => $next,
            'chapter' => $chapter
        ];

    }

    public function getLatestChapterByManga(string $manga) {
        $next = Chapter::withTrashed()->whereRelation('manga', 'slug', $manga)->latest('created_at')->first();

        if (empty($next)) {
            return 0;
        }

        return (int)$next['number'];
    }
}
