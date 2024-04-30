<?php

namespace Modules\Gallery\Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;

class GalleryDatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Model::unguard();
        if (strtolower(\Config::get('app.demo_mode')) == 'yes'):
            $this->call(ImageTableSeeder::class);
            $this->call(AudioTableSeeder::class);
            $this->call(VideoTableSeeder::class);
            $this->call(AudioPostTableSeeder::class);
            $this->call(AlbumTableSeeder::class);
            $this->call(GalleryImageTableSeeder::class);
        endif;
    }
}
