<?php

namespace Modules\Gallery\Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;
use DB;

class AudioTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {

        DB::statement("INSERT INTO audio (id, audio_name, disk, original, audio_mp3, audio_ogg, duration, audio_type, created_at, updated_at) VALUES
             (1, 'All This Is - Smith St. Basement.wav', 'local', 'audios/20201217183511_original_audio32.wav', '', '', '', 'wav', '2020-12-17 12:35:11', '2020-12-17 12:35:11'),
(2, 'My Morning Jacket - Phone Went West.wav', 'local', 'audios/20201217183516_original_audio29.wav', '', '', '', 'wav', '2020-12-17 12:35:16', '2020-12-17 12:35:16'),
(3, 'Symphony No.6 (1st movement).mp3', 'local', 'audios/20201217183519_original_audio28.mp3', '', '', '', 'mp3', '2020-12-17 12:35:19', '2020-12-17 12:35:19'),
(4, 'The Slackers - Married Girl.mp3', 'local', 'audios/20201217183523_original_audio39.mp3', '', '', '', 'mp3', '2020-12-17 12:35:23', '2020-12-17 12:35:23'),
(5, 'G. Love & Special Sauce - Dreamin.mp3', 'local', 'audios/20201217183525_original_audio13.mp3', '', '', '', 'mp3', '2020-12-17 12:35:25', '2020-12-17 12:35:25')");
        Model::unguard();

        // $this->call("OthersTableSeeder");
    }
}
