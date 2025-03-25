<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $categories = [
            ['name' => 'Suyuq'],
            ['name' => 'Qo\'yiq'],
            ['name' => 'Go\'shtli'],
            ['name' => 'Go\'shtsiz'],
            ['name' => 'Suyuq'],
            ['name' => 'Fast food'],
        ];

        Category::insert($categories);
    }
}
