<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\JobType;

class JobTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $jobtypes = [
            ['name' => 'Full-time'],
            ['name' => 'Part-time'],
            ['name' => 'Contract'],
            ['name' => 'Freelance'],
            ['name' => 'Internship'],
            ['name' => 'Remote'],
            ['name' => 'Entry-level'],
            ['name' => 'Volunteer'],
            ['name' => 'Apprenticeship'],

            // Add more categories as needed

        ];

        JobType::insert($jobtypes);

    }
}
