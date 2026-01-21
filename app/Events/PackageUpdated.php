<?php

namespace App\Events;

use App\Models\Package;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class PackageUpdated
{
    use Dispatchable, SerializesModels;

    public function __construct(
        public Package $package,
        public array $changes = []
    ) {}
}
