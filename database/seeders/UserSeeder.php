<?php

namespace Database\Seeders;

use App\Models\User;
use Hash;
use Illuminate\Database\Seeder;
use Str;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
       // create user admin
        User::create([
            'name' => 'admin',
            'email' => 'admin@example.com',
            'username' => 'admin',
            'email_verified_at' => now(),
            'password' => Hash::make('8NOVMjsQs3f99uj'),
            'remember_token' => Str::random(10),
        ]);
        //create user
        User::factory(10)->create();
    }
}
