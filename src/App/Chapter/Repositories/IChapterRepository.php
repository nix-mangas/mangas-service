<?php

namespace App\Chapter\Repositories;

use App\Chapter\Models\Chapter;

interface IChapterRepository {
    public function latest(array $filters);

    public function latestEagerLoading(array $filters);

    public function listLatestFromVip(array $filters);

    public function listPagesByChapter(string $chapter);

    public function findById(string $id);

    public function listByManga(string $manga, array $filters);

    public function exists(string $manga, string $number): bool;

    public function create(array $data): Chapter;

    public function save(string $id, array $data): void;

    public function savePages(string $id, array $data);

    public function delete(string $id): void;

    public function destroy(string $id): void;

    public function restore(string $id): void;

    public function getByMangaAndNumber(string $manga, string $number);

    public function getLatestChapterByManga(string $manga);
}
