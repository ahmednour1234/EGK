<?php

namespace App\Repositories\Contracts;

interface CityRepositoryInterface
{
    public function getAll(array $filters = []);
    public function getById(int $id);
}

