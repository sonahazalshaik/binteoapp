<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $categories = [
            'Lifestyle', 'Music and Dance', 'Food and Recipes', 'Fashion and Beauty', 
            'Talent Shows', 'Science and Technology', 'Pets and Animals', 
            'People and Blogs', 'News and Politics', 'Gaming', 'Cars and Vehicles', 
            'Film and Animation', 'Kids Content'
        ];

        foreach ($categories as $name) {
            Category::firstOrCreate(
                ['name' => $name],
                ['slug' => Str::slug($name)]
            );
        }
    }
}
