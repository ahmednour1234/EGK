<?php

namespace App\Repositories\Contracts;

interface PageRepositoryInterface
{
    public function getAll(array $filters = []);
    public function getBySlug(string $slug);
}

