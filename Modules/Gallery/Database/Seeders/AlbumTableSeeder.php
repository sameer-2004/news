<?php

namespace Modules\Gallery\Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;
use DB;
use Modules\Gallery\Entities\Album;

class AlbumTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {

        Album::create([
            'language' => 'en',
            'name' => 'Nature',
            'slug' => 'nature',
            'tabs' => 'Mountains,Cities',
            'order' => '0',
            'meta_keywords' => 'nature,album-images',
            'meta_description' => 'Nature images',
            'disk' => 'local',
            'original_image' => 'images/20201219120053_galleryImage_big15.jpg',
            'thumbnail' => 'images/20201219120053_galleryImage_thumb10.jpg',
            'created_at' => '2020-12-19 06:00:54',
            'updated_at' => '2020-12-19 06:09:03',
        ]);

        Album::create([
            'language' => 'en',
            'name' => 'Travel',
            'slug' => 'travel',
            'tabs' => 'Adventure,Beach,Others',
            'order' => '0',
            'meta_keywords' => 'travels, adventure, album-images',
            'meta_description' => 'Travels images',
            'disk' => 'local',
            'original_image' => 'images/20201219120248_galleryImage_big40.jpg',
            'thumbnail' => 'images/20201219120248_galleryImage_thumb46.jpg',
            'created_at' => '2020-12-19 06:02:49',
            'updated_at' => '2020-12-19 06:02:49',
        ]);

        Album::create([
            'language' => 'en',
            'name' => 'Children',
            'slug' => 'children',
            'tabs' => 'Children Playing,Cute,Toys',
            'order' => '0',
            'meta_keywords' => 'childrens, cute, playing',
            'meta_description' => 'Cute childrens images',
            'disk' => 'local',
            'original_image' => 'images/20201219120436_galleryImage_big30.jpg',
            'thumbnail' => 'images/20201219120436_galleryImage_thumb2.jpg',
            'created_at' => '2020-12-19 06:04:38',
            'updated_at' => '2020-12-19 06:04:38',
        ]);

        Model::unguard();

        // $this->call("OthersTableSeeder");
    }
}
