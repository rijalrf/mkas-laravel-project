<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Seeder;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $categories = Category::getStaticList();

        foreach ($categories as $cat) {
            Category::updateOrCreate(
                ['id' => $cat['id']],
                [
                    'name' => $cat['name'],
                    // 'icon' and 'color' can be stored if you added columns, 
                    // but we'll use the static list for UI logic.
                ]
            );
        }
    }
}
