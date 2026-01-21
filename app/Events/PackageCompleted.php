<?php

namespace App\Events;

use App\Models\Package;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class PackageCompleted
{
    use Dispatchable, SerializesModels;

    public function __construct(
        public Package $package
    ) {}
}
