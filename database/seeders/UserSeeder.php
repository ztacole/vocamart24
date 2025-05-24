<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
         DB::table('users')->insert([
            [
                'name' => 'User Biasa',
                'email' => 'user@example.com',
                'password' => Hash::make('password'),
                'role_id' => 1,
            ],
            [
                'name' => 'Admin RPL',
                'email' => 'rpl@admin.com',
                'password' => Hash::make('password'),
                'role_id' => 2,
            ],
            [
                'name' => 'Admin TBG',
                'email' => 'tbg@admin.com',
                'password' => Hash::make('password'),
                'role_id' => 3,
            ],
            [
                'name' => 'Admin TBS',
                'email' => 'tbs@admin.com',
                'password' => Hash::make('password'),
                'role_id' => 4,
            ],
            [
                'name' => 'Admin PH',
                'email' => 'ph@admin.com',
                'password' => Hash::make('password'),
                'role_id' => 5,
            ],
            [
                'name' => 'Admin ULW',
                'email' => 'ulw@admin.com',
                'password' => Hash::make('password'),
                'role_id' => 6,
            ],
        ]);
    }
}
