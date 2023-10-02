<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Http\Repositories\UserRepository;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        (new UserRepository())->firstOrCreate([
            'email' => 'admin@gmail.com'
        ], [
            'password' => Hash::make('password'),
            'name' => 'Super User',
            'email_verified_at' => now()
        ]);
    }
}
