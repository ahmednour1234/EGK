<?php

namespace App\Repositories\Contracts;

interface SettingRepositoryInterface
{
    public function getAll(array $filters = []): array;
    public function getByKey(string $key): ?array;
    public function getByKeys(array $keys): array;
}

