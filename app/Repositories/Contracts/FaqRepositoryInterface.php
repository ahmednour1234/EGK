<?php

namespace App\Repositories\Contracts;

interface FaqRepositoryInterface
{
    public function getAll(array $filters = []);
    public function getById(int $id);
}

