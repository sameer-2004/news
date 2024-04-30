<?php

namespace Modules\Post\Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;
use Modules\Post\Entities\SubCategory;

class SeedSubCategoryTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        SubCategory::create([
            'sub_category_name' => 'Bangladesh',
            'slug'              => 'politics',
            'category_id'       => '1',
            'language'          => 'en',
        ]);

        if (strtolower(\Config::get('app.demo_mode')) == 'yes'):

            SubCategory::create([
                'sub_category_name' => 'Computer Science',
                'slug'              => 'computer-science',
                'category_id'       => '2',
                'language'          => 'en',
            ]);

            SubCategory::create([
                'sub_category_name' => 'Life Style',
                'slug'              => 'life-style',
                'category_id'       => '3',
                'language'          => 'en',
            ]);

            SubCategory::create([
                'sub_category_name' => 'Nasa',
                'slug'              => 'nasa',
                'category_id'       => '4',
                'language'          => 'en',
            ]);

            SubCategory::create([
                'sub_category_name' => 'Wired',
                'slug'              => 'wired',
                'category_id'       => '4',
                'language'          => 'en',
            ]);

            SubCategory::create([
                'sub_category_name' => 'ABC News',
                'slug'              => 'abc-news',
                'category_id'       => '4',
                'language'          => 'en',
            ]);


            SubCategory::create([
                'sub_category_name' => 'بنغلاديش' ,
                'slug'              => 'بنغلاديش',
                'category_id'       => '5',
                'language'          => 'ar',
            ]);

            SubCategory::create([
                'sub_category_name' => 'علوم الكمبيوتر',
                'slug'              => 'علوم-الكمبيوتر',
                'category_id'       => '6',
                'language'          => 'ar',
            ]);

        endif;
            Model::unguard();

        // $this->call("OthersTableSeeder");
    }
}
