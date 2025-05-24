<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class RoleSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('roles')->insert([
            ['access' => 'user'],
            ['access' => 'admin rpl'],
            ['access' => 'admin tbg'],
            ['access' => 'admin tbs'],
            ['access' => 'admin ph'],
            ['access' => 'admin ulw'],
        ]);
    }
}

