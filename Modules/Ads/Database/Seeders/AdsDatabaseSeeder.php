<?php

namespace Modules\Ads\Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class AdsDatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Model::unguard();
        $this->call(SeedAdsTableSeeder::class);

        $data = [
            [
                'title' => 'Top Banner',
                'unique_name' => 'top_banner',
                'status' => 1,
            ],
            [
                'title' => 'Home Page Middle',
                'unique_name' => 'home_page_middle',
                'status' => 1,
            ],
            [
                'title' => 'Home Page Bottom',
                'unique_name' => 'home_page_bottom',
                'status' => 1,
            ],
            [
                'title' => 'Widget',
                'unique_name' => 'widget',
                'status' => 1,
            ],
        ];

        DB::table('ad_locations')->insert($data);
    }
}
