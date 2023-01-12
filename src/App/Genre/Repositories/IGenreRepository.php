<?php

namespace App\Genre\Repositories;

interface IGenreRepository {
    public function list(array $filters);

    public function exists(string $slug): bool;

    public function create(array $data): void;

    public function save(string $id, array $data): void;

    public function delete(string $id): void;

    public function destroy(string $id): void;

    public function restore(string $id): void;
}
