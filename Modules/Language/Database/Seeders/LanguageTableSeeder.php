<?php

namespace Modules\Language\Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;
use Modules\Language\Entities\Language;

class LanguageTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Model::unguard();

        Language::create(['name'=> 'English','code' => 'en', 'icon_class' => 'flag-icon flag-icon-us',  'text_direction' => 'LTR','status' => 'active']);
        if (strtolower(\Config::get('app.demo_mode')) == 'yes'):
            Language::create(['name'=> 'Arabic','code' => 'ar', 'icon_class' => 'flag-icon flag-icon-ae',  'text_direction' => 'RTL','status' => 'active']);
        endif;
    }

}
