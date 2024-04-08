<?php

namespace Database\Seeders;

use App\Models\Author;
use App\Models\Book;
use App\Models\Section;
use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        User::factory()->create([
            'name' => 'First User',
            'email' => 'first@mail.com',
            'password' => bcrypt('123123123'),
        ]);

        User::factory()->create([
            'name' => 'Second User',
            'email' => 'second@mail.com',
            'password' => bcrypt('123123123'),
        ]);

        User::factory()->create([
            'name' => 'Admin',
            'password' => bcrypt('123123123'),
            'email' => 'admin@mail.com',
            'is_admin' => 1,
        ]);

        Section::factory()->count(10)->has(
            Book::factory()->count(10)->has(
                Author::factory()->count(1)
            ))->create();

    }
}
