<?php

namespace App\Jobs\Middleware;

use App\Jobs\Traits\AuthenticatableJobTrait;
use Illuminate\Support\Arr;

/**
 * Class AuthenticatableMiddleware
 *
 * This job middleware will use the AuthenticatableJobTrait (if one exists), to authenticate as the required user within a queued job
 * Only relevant if the driver is not 'sync' (otherwise the authentication is maintained already)
 *
 * @package App\Jobs\Middleware
 */
class AuthenticatableJobMiddleware
{
    /**
     * This executes the middleware code
     *
     * @param $job mixed|Object The relevant job class
     * @param $next mixed|Closure Executes the job
     */
    public function handle($job, $next)
    {
        if ($this->doesJobQualify($job)) {
            auth()->login($job->getAuthenticatedUser());
        }

        // Executes the job
        $next($job);

        if ($this->doesJobQualify($job)) {
            auth()->logout();
        }
    }

    /**
     * Checks if the given Job qualifies for the middleware
     *
     * @param $job mixed|Object
     * @return bool
     */
    protected function doesJobQualify($job): bool
    {
        if (Arr::has(class_uses($job), AuthenticatableJobTrait::class) && config('queue.default') != 'sync') {
            if (! is_null($job->getAuthenticatedUser())) {
                return true;
            }
        }

        return false;
    }
}
