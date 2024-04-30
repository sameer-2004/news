<?php

namespace Modules\Post\Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;
use Modules\Post\Entities\Category;

class SeedCategoryTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Category::create([
            'category_name' => 'World' ,
            'language'      => 'en',
            'slug'          => 'world',
            'order'         => '1'
        ]);
        if (strtolower(\Config::get('app.demo_mode')) == 'yes'):
            Category::create([
                'category_name' => 'Science' ,
                'language'      => 'en',
                'slug'          => 'science',
                'order'         => '2'
            ]);

            Category::create([
                'category_name' => 'Life Style' ,
                'language'      => 'en',
                'slug'          => 'life-style',
                'order'         => '3'
            ]);

            Category::create([
                'category_name' => 'RSS News' ,
                'language'      => 'en',
                'slug'          => 'rss-news',
                'order'         => '4'
            ]);


            Category::create([
                'category_name' => 'العالمية' ,
                'language'      => 'ar',
                'slug'          => 'العالمية',
                'order'         => '1'
            ]);

            Category::create([
                'category_name' => 'علم' ,
                'language'      => 'ar',
                'slug'          => 'علم',
                'order'         => '2'
            ]);
        endif;
        Model::unguard();

        // $this->call("OthersTableSeeder");
    }
}
