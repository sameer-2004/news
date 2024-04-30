<?php

namespace Modules\Gallery\Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;
use DB;

class VideoTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::statement("INSERT INTO videos (id, video_name, video_thumbnail, disk, original, v_144p, v_240p, v_360p, v_480p, v_720p, v_1080p, video_type, created_at, updated_at)  VALUES
             (1, '20201217183431_original_11', 'videos/thumbnail/20201217183431_original_11.jpg', 'local', 'videos/20201217183431_original_11.mp4', 'videos/20201217183452_256x144_31.mp4', 'videos/20201217183452_320x240_48.mp4', 'videos/20201217183452_480x360_2.mp4', 'videos/20201217183452_858x480_3.mp4', 'videos/20201217183452_1280x720_45.mp4', 'videos/20201217183452_1920x1080_4.mp4', 'mp4', '2020-12-17 12:34:32', '2020-12-17 12:35:28'),
(2, '20201217183434_original_31', 'videos/thumbnail/20201217183434_original_31.jpg', 'local', 'videos/20201217183434_original_31.mp4', 'videos/20201217183528_256x144_8.mp4', 'videos/20201217183528_320x240_35.mp4', 'videos/20201217183528_480x360_10.mp4', 'videos/20201217183528_858x480_31.mp4', 'videos/20201217183528_1280x720_34.mp4', NULL, 'mp4', '2020-12-17 12:34:35', '2020-12-17 12:36:47'),
(3, '20201217183437_original_22', 'videos/thumbnail/20201217183437_original_22.jpg', 'local', 'videos/20201217183437_original_22.mp4', 'videos/20201217183647_256x144_1.mp4', 'videos/20201217183647_320x240_18.mp4', 'videos/20201217183647_480x360_37.mp4', 'videos/20201217183647_858x480_9.mp4', 'videos/20201217183647_1280x720_21.mp4', NULL, 'mp4', '2020-12-17 12:34:38', '2020-12-17 12:36:56'),
(4, '20201217183440_original_33', 'videos/thumbnail/20201217183440_original_33.jpg', 'local', 'videos/20201217183440_original_33.mp4', 'videos/20201217183656_256x144_2.mp4', 'videos/20201217183656_320x240_24.mp4', 'videos/20201217183656_480x360_15.mp4', 'videos/20201217183656_858x480_11.mp4', 'videos/20201217183656_1280x720_50.mp4', 'videos/20201217183656_1920x1080_18.mp4', 'mp4', '2020-12-17 12:34:41', '2020-12-17 12:38:46'),
(5, '20201217183443_original_12', 'videos/thumbnail/20201217183443_original_12.jpg', 'local', 'videos/20201217183443_original_12.mp4', 'videos/20201217183846_256x144_49.mp4', 'videos/20201217183846_320x240_45.mp4', 'videos/20201217183846_480x360_17.mp4', 'videos/20201217183846_858x480_10.mp4', 'videos/20201217183846_1280x720_24.mp4', 'videos/20201217183846_1920x1080_49.mp4', 'mp4', '2020-12-17 12:34:45', '2020-12-17 12:39:19')");

        Model::unguard();

        // $this->call("OthersTableSeeder");
    }
}
