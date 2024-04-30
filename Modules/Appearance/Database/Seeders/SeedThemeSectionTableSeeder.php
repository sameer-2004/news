<?php

namespace Modules\Appearance\Database\Seeders;

use Illuminate\Database\Seeder;
use Modules\Appearance\Entities\ThemeSection;
use Illuminate\Database\Eloquent\Model;
use DB;

class SeedThemeSectionTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');

        if (strtolower(\Config::get('app.demo_mode')) == 'yes'):

            ThemeSection::create([
                'label'         => 'Primary Section',
                'theme_id'      => '1',
                'type'          => '0',
                'order'         => '1',
                'post_amount'   => '1',
                'section_style' => 'style_1',
                'is_primary'    => '1',
                'status'    => '1'
            ]);
            ThemeSection::create([
                'label'         => 'World',
                'theme_id'      => '1',
                'type'          => '1',
                'order'         => '1',
                'category_id'   => '1',
                'section_style' => 'style_1',
                'is_primary'    => '0',
                'status'        => '1',
                'language'        => 'en'
            ]);
            ThemeSection::create([
                'label'         => 'Science',
                'theme_id'      => '1',
                'type'          => '1',
                'order'         => '2',
                'category_id'   => '2',
                'section_style' => 'style_2',
                'is_primary'    => '0',
                'status'        => '1',
                'ad_id'        => '1',
                'language'        => 'en'
            ]);
            ThemeSection::create([
                'label'         => 'RSS News',
                'theme_id'      => '1',
                'type'          => '1',
                'order'         => '3',
                'category_id'   => '4',
                'section_style' => 'style_3',
                'is_primary'    => '0',
                'status'        => '1',
                'ad_id'        => '1',
                'language'        => 'en'
            ]);
            ThemeSection::create([
                'label'         => 'Life Style',
                'theme_id'      => '1',
                'type'          => '1',
                'order'         => '4',
                'category_id'   => '3',
                'section_style' => 'style_4',
                'is_primary'    => '0',
                'status'        => '1',
                'ad_id'        => '1',
                'language'        => 'en'
            ]);
            ThemeSection::create([
                'label'         => 'videos',
                'theme_id'      => '1',
                'type'          => '2',
                'order'         => '5',
                'section_style' => 'style_1',
                'is_primary'    => '0',
                'status'        => '1'
            ]);
            ThemeSection::create([
                'label'         => 'latest_post',
                'theme_id'      => '1',
                'type'          => '3',
                'order'         => '6',
                'is_primary'    => '0',
                'status'        => '1'
            ]);

            // for arabic language
            ThemeSection::create([
                'label'         => 'العالمية',
                'theme_id'      => '1',
                'type'          => '1',
                'order'         => '1',
                'category_id'   => '4',
                'section_style' => 'style_1',
                'is_primary'    => '0',
                'status'        => '1',
                'language'        => 'ar'
            ]);
            ThemeSection::create([
                'label'         => 'العالمية',
                'theme_id'      => '1',
                'type'          => '1',
                'order'         => '2',
                'category_id'   => '4',
                'section_style' => 'style_1',
                'is_primary'    => '0',
                'status'        => '1',
                'language'        => 'ar'
            ]);

            ThemeSection::create([
                'label'         => 'علم',
                'theme_id'      => '1',
                'type'          => '1',
                'order'         => '3',
                'category_id'   => '5',
                'section_style' => 'style_2',
                'is_primary'    => '0',
                'status'        => '1',
                'language'        => 'ar'
            ]);
        else:
            ThemeSection::create([
                'label'         => 'Primary Section',
                'theme_id'      => '1',
                'type'          => '0',
                'order'         => '1',
                'post_amount'   => '1',
                'section_style' => 'style_1',
                'is_primary'    => '1',
                'status'        => '1'
            ]);

            ThemeSection::create([
                'label'         => 'latest_post',
                'theme_id'      => '1',
                'type'          => '3',
                'order'         => '6',
                'is_primary'    => '0',
                'status'        => '1'
            ]);
        endif;

        Model::unguard();

        // $this->call("OthersTableSeeder");
    }
}
