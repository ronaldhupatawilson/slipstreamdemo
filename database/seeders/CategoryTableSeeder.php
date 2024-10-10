<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Category;

class CategoryTableSeeder extends Seeder
{
    public function run()
    {
        Category::factory()
            ->count(300)
            ->create();
    }
}
