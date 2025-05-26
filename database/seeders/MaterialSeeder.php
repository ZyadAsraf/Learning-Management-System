<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Material;

class MaterialSeeder extends Seeder
{
    public function run(): void
    {
        Material::insert([
            [
                'title' => 'Intro to Laravel',
                'content' => 'Basics of Laravel framework.', // modified from 'description'
                'pdf_path' => 'materials/intro-laravel.pdf', // modified from 'file_path'
                'course_id' => 1,
            ],
            [
                'title' => 'Advanced PHP',
                'content' => 'OOP in PHP and best practices.',
                'pdf_path' => 'materials/advanced-php.pdf',
                'course_id' => 1,
            ],
        ]);
    }
}
