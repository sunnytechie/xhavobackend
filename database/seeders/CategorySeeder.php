<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Define how many categories you want to create
        $numberOfCategories = 15;

        // Create categories using the factory
        Category::factory($numberOfCategories)->create();

    }
}
