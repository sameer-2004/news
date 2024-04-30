<?php

namespace Modules\Ads\Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;
use Modules\Ads\Entities\Ad;
use Faker\Factory as Faker;
use DB;

class SeedAdsTableSeeder extends Seeder
{
	/**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
    	DB::statement('SET FOREIGN_KEY_CHECKS=0;');

    	$ad              = new Ad();
        $ad->ad_name     = 'Buy Now';
        $ad->ad_size     = "970*90";
        $ad->ad_type     = 'image';
        $ad->ad_url      = '';
		$ad->ad_image_id = 51;
        $ad->save();

        Model::unguard();
    }
}
