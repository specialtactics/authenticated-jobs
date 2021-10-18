<?php

namespace App\Jobs\Traits;

use App\Jobs\Middleware\AuthenticatableJobMiddleware;
use App\Models\User;

/**
 * Add this trait along with BootingTraits to any job you want to authenticate whoever triggered the job
 * Remember to fire constructTraits() in the Job's constructor.
 *
 * Trait AuthenticatableTrait
 * @package App\Jobs\Features
 */
trait AuthenticatableJobTrait
{
    /**
     * @var User The user whom triggered the job
     */
    protected $authenticatedUser = null;

    /**
     * Returns middlewares for the job. In our case, we want to use the authenticatable middleware
     *
     * @return array
     */
    public function middleware()
    {
        return [new AuthenticatableJobMiddleware];
    }

    /**
     * Initialise the trait
     *
     * This is called by Bootable
     */
    public function initializeAuthenticatableJobTrait()
    {
        $user = auth()->user();

        if ($user) {
            $this->authenticatedUser = $user;
        }
    }

    /**
     * @return User|null
     */
    public function getAuthenticatedUser(): ?User
    {
        return $this->authenticatedUser;
    }
}
