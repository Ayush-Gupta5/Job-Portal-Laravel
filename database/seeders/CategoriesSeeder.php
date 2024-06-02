<?php

namespace Database\Seeders;
use App\Models\Category;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CategoriesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $categories = [
            ['name' => 'Information Technology (IT)'],
            ['name' => 'Engineering & Architecture'],
            ['name' => 'Sales & Marketing'],
            ['name' => 'Finance & Accounting'],
            ['name' => 'Education & Training'],
            ['name' => 'Creative & Design'],
            ['name' => 'Human Resources'],
            ['name' => 'Media & Communications'],
            ['name' => 'Healthcare & Medicine'],

            // Add more categories as needed

        ];

        Category::insert($categories);
    }
}
