<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $sid = Str::ulid();
        DB::table('users')->insert([
            'id' => $sid,
            'username' => 'restuqubro',
            'firstname' => 'Restu',
            'lastname' => 'Alqubro',
            'email' => 'restualqubro@gmail.com',
            'email_verified_at' => now(),
            'password' => Hash::make('BecakLaju195'),
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }
}
