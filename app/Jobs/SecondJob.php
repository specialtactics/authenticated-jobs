<?php

namespace App\Jobs;

use App\Jobs\Traits\AuthenticatableJobTrait;
use App\Models\User;
use App\Traits\BootsTraits;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class SecondJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;
    use BootsTraits, AuthenticatableJobTrait;

    /**
     * FirstJob constructor.
     */
    public function __construct()
    {
        static::constructTraits();
    }

    /**
     * Runs job
     */
    public function handle()
    {
        // Nothing
    }
}
