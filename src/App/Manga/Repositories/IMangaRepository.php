<?php

namespace App\Manga\Repositories;

interface IMangaRepository {
    public function list(array $filters);

    public function listByGenre(string $genre, array $filters);

    public function findById(string $id);

    public function findBySlug(string $slug);

    public function exists(string $slug): bool;

    public function create(array $data): void;

    public function save(string $id, array $data): void;

    public function delete(string $id): void;

    public function destroy(string $id): void;

    public function restore(string $id): void;
}
