<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Category;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Category::insert([
            ['name' => "Suyuq"],
            ['name' => "Qo'yiq"],
            ['name' => "Fast food"],
        ]);
    }
}
