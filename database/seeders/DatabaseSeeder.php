<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        if (config('app.env') === 'local') {
            if (User::firstWhere('email', 'admin@example.com') === null) {
                User::create([
                    'email' => 'admin@example.com',
                    'name' => 'Admin',
                    'password' => 'password',
                ]);
            }
        }
    }
}
