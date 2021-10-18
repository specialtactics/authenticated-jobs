<?php

namespace Database\Seeders;

use App\Models\Role;
use App\Models\User;

class UserStorySeeder extends BaseSeeder
{
    /**
     * Credentials
     */
    const ADMIN_CREDENTIALS = [
        'email' => 'admin@admin.com',
    ];

    public function runFake()
    {
        // Grab all roles for reference
        $roles = Role::all();

        // Create regular user
        \App\Models\User::factory()->create([
            'name'         => 'Bob',
            'email'        => 'bob@bob.com',
            'primary_role' => $roles->where('name', 'regular')->first()->role_id,
        ]);
    }
}
