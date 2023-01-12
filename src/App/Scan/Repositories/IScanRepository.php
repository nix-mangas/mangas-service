<?php

namespace App\Scan\Repositories;

interface IScanRepository
{
    public function list(array $filters);
    public function getAllMangasByScan(string $scan);
    public function getAllMembersByScan(string $scan);
    public function exists(string $slug);
    public function findById(string $id);
    public function findBySlug(string $slug);
    public function create(array $data);
    public function save(string $id, array $data);
    public function delete(string $id);
    public function destroy(string $id);
    public function restore(string $id);
}
