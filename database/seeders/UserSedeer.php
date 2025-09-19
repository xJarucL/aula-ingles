<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class UserSedeer extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User::create([
            'name' => 'Prof. Jaru',
            'email' => 'jaruny.cl@gmail.com',
            'password' => Hash::make('12345'),
            'email_verified_at' => now()
        ]);
    }
}
