<?php

namespace App\People\Repositories;

interface IPeopleRepository {
    public function list(array $filters);

    public function listAllWorksByPeople(string $people, array $filters);

    public function exists(string $people);

    public function create(array $data);

    public function save(string $id, array $data);

    public function delete(string $id);

    public function destroy(string $id);

    public function restore(string $id);
}
