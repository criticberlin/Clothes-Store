<?php

namespace Database\Seeders;

use App\Models\Size;
use Illuminate\Database\Seeder;

class SizeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $sizes = [
            ['name' => 'XS'],
            ['name' => 'S'],
            ['name' => 'M'],
            ['name' => 'L'],
            ['name' => 'XL'],
            ['name' => 'XXL'],
            ['name' => '36'],
            ['name' => '38'],
            ['name' => '40'],
            ['name' => '42'],
            ['name' => '44'],
            ['name' => '46'],
        ];

        foreach ($sizes as $size) {
            Size::create($size);
        }
    }
} 