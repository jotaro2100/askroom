<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;

use App\Models\User;
use App\Models\Query;
use App\Models\Answer;
use App\Models\Addition;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $users = User::factory(20)->create();
        $queries = Query::factory(30)->recycle($users)->create();
        $answers = Answer::factory(50)->recycle($users)->recycle($queries)->create();
        Addition::factory(80)->recycle($users)->recycle($answers)->create();
    }
}
