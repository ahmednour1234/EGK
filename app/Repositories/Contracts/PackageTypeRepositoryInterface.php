<?php

namespace App\Repositories\Contracts;

interface PackageTypeRepositoryInterface
{
    public function getAll(array $filters = []);
    public function getById(int $id);
    public function getBySlug(string $slug);
}

