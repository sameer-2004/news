<?php

namespace Modules\Gallery\Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;
use DB;

class GalleryImageTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::statement("INSERT INTO gallery_images (id, disk, album_id, tab, title, is_cover, original_image, thumbnail, created_at, updated_at) VALUES
            (1, 'local', 1, 'Mountains', 'Mountains Image', 0, 'images/20201219120650_galleryImage_big37.jpg', 'images/20201219120650_galleryImage_thumb13.jpg', '2020-12-19 06:06:53', '2020-12-19 06:06:53'),
(2, 'local', 1, 'Mountains', 'Mountains Image', 0, 'images/20201219120653_galleryImage_big29.jpg', 'images/20201219120653_galleryImage_thumb19.jpg', '2020-12-19 06:06:54', '2020-12-19 06:06:54'),
(3, 'local', 1, 'Mountains', 'Mountains Image', 0, 'images/20201219120654_galleryImage_big16.jpg', 'images/20201219120654_galleryImage_thumb11.jpg', '2020-12-19 06:06:55', '2020-12-19 06:06:55'),
(4, 'local', 1, 'Mountains', 'Mountains Image', 0, 'images/20201219120655_galleryImage_big29.jpg', 'images/20201219120655_galleryImage_thumb50.jpg', '2020-12-19 06:06:56', '2020-12-19 06:06:56'),
(5, 'local', 1, 'Mountains', 'Mountains Image', 0, 'images/20201219120656_galleryImage_big35.jpg', 'images/20201219120656_galleryImage_thumb7.jpg', '2020-12-19 06:06:58', '2020-12-19 06:06:58'),
(6, 'local', 1, 'Cities', 'City Image', 0, 'images/20201219120833_galleryImage_big48.jpg', 'images/20201219120833_galleryImage_thumb38.jpg', '2020-12-19 06:08:34', '2020-12-19 06:08:34'),
(7, 'local', 1, 'Cities', 'City Image', 0, 'images/20201219120834_galleryImage_big29.jpg', 'images/20201219120834_galleryImage_thumb17.jpg', '2020-12-19 06:08:36', '2020-12-19 06:08:36'),
(8, 'local', 1, 'Cities', 'City Image', 0, 'images/20201219120836_galleryImage_big11.jpg', 'images/20201219120836_galleryImage_thumb3.jpg', '2020-12-19 06:08:39', '2020-12-19 06:08:39'),
(9, 'local', 1, 'Cities', 'City Image', 0, 'images/20201219120839_galleryImage_big10.jpg', 'images/20201219120839_galleryImage_thumb16.jpg', '2020-12-19 06:08:43', '2020-12-19 06:08:43'),
(10, 'local', 1, 'Cities', 'City Image', 0, 'images/20201219120843_galleryImage_big12.jpg', 'images/20201219120843_galleryImage_thumb5.jpg', '2020-12-19 06:08:45', '2020-12-19 06:08:45'),
(11, 'local', 2, 'Adventure', 'Adventuring', 0, 'images/20201219121029_galleryImage_big27.jpg', 'images/20201219121029_galleryImage_thumb23.jpg', '2020-12-19 06:10:30', '2020-12-19 06:10:30'),
(12, 'local', 2, 'Adventure', 'Adventuring', 0, 'images/20201219121030_galleryImage_big44.jpg', 'images/20201219121030_galleryImage_thumb12.jpg', '2020-12-19 06:10:33', '2020-12-19 06:10:33'),
(13, 'local', 2, 'Adventure', 'Adventuring', 0, 'images/20201219121033_galleryImage_big40.jpg', 'images/20201219121033_galleryImage_thumb36.jpg', '2020-12-19 06:10:34', '2020-12-19 06:10:34'),
(14, 'local', 2, 'Adventure', 'Adventuring', 0, 'images/20201219121034_galleryImage_big36.jpg', 'images/20201219121034_galleryImage_thumb34.jpg', '2020-12-19 06:10:40', '2020-12-19 06:10:40'),
(15, 'local', 2, 'Beach', NULL, 0, 'images/20201219121141_galleryImage_big17.jpg', 'images/20201219121141_galleryImage_thumb2.jpg', '2020-12-19 06:11:42', '2020-12-19 06:11:42'),
(16, 'local', 2, 'Beach', NULL, 0, 'images/20201219121142_galleryImage_big27.jpg', 'images/20201219121142_galleryImage_thumb20.jpg', '2020-12-19 06:11:43', '2020-12-19 06:11:43'),
(17, 'local', 2, 'Beach', NULL, 0, 'images/20201219121143_galleryImage_big33.jpg', 'images/20201219121143_galleryImage_thumb28.jpg', '2020-12-19 06:11:44', '2020-12-19 06:11:44'),
(18, 'local', 2, 'Others', NULL, 0, 'images/20201219121312_galleryImage_big14.jpg', 'images/20201219121312_galleryImage_thumb9.jpg', '2020-12-19 06:13:14', '2020-12-19 06:13:14'),
(19, 'local', 2, 'Others', NULL, 0, 'images/20201219121314_galleryImage_big18.jpg', 'images/20201219121314_galleryImage_thumb34.jpg', '2020-12-19 06:13:15', '2020-12-19 06:13:15'),
(20, 'local', 2, 'Others', NULL, 0, 'images/20201219121315_galleryImage_big2.jpg', 'images/20201219121315_galleryImage_thumb6.jpg', '2020-12-19 06:13:17', '2020-12-19 06:13:17'),
(21, 'local', 2, 'Others', NULL, 0, 'images/20201219121317_galleryImage_big5.jpg', 'images/20201219121317_galleryImage_thumb40.jpg', '2020-12-19 06:13:18', '2020-12-19 06:13:18'),
(22, 'local', 2, 'Others', NULL, 0, 'images/20201219121318_galleryImage_big39.jpg', 'images/20201219121318_galleryImage_thumb49.jpg', '2020-12-19 06:13:19', '2020-12-19 06:13:19'),
(23, 'local', 2, 'Others', NULL, 0, 'images/20201219121319_galleryImage_big20.jpg', 'images/20201219121319_galleryImage_thumb40.jpg', '2020-12-19 06:13:20', '2020-12-19 06:13:20'),
(24, 'local', 3, 'Children Playing', NULL, 0, 'images/20201219121402_galleryImage_big38.jpg', 'images/20201219121402_galleryImage_thumb12.jpg', '2020-12-19 06:14:05', '2020-12-19 06:14:05'),
(25, 'local', 3, 'Children Playing', NULL, 0, 'images/20201219121405_galleryImage_big42.jpg', 'images/20201219121405_galleryImage_thumb50.jpg', '2020-12-19 06:14:08', '2020-12-19 06:14:08'),
(26, 'local', 3, 'Children Playing', NULL, 0, 'images/20201219121408_galleryImage_big31.jpg', 'images/20201219121408_galleryImage_thumb5.jpg', '2020-12-19 06:14:09', '2020-12-19 06:14:09'),
(27, 'local', 3, 'Children Playing', NULL, 0, 'images/20201219121409_galleryImage_big23.jpg', 'images/20201219121409_galleryImage_thumb34.jpg', '2020-12-19 06:14:12', '2020-12-19 06:14:12'),
(28, 'local', 3, 'Cute', 'Cute Kids', 0, 'images/20201219121553_galleryImage_big41.jpg', 'images/20201219121553_galleryImage_thumb16.jpg', '2020-12-19 06:15:53', '2020-12-19 06:15:53'),
(29, 'local', 3, 'Cute', 'Cute Kids', 0, 'images/20201219121553_galleryImage_big47.jpg', 'images/20201219121553_galleryImage_thumb29.jpg', '2020-12-19 06:15:54', '2020-12-19 06:15:54'),
(30, 'local', 3, 'Cute', 'Cute Kids', 0, 'images/20201219121554_galleryImage_big5.jpg', 'images/20201219121554_galleryImage_thumb19.jpg', '2020-12-19 06:15:56', '2020-12-19 06:15:56'),
(31, 'local', 3, 'Cute', 'Cute Kids', 0, 'images/20201219121556_galleryImage_big40.jpg', 'images/20201219121556_galleryImage_thumb11.jpg', '2020-12-19 06:15:58', '2020-12-19 06:15:58'),
(32, 'local', 3, 'Cute', 'Cute Kids', 0, 'images/20201219121558_galleryImage_big22.jpg', 'images/20201219121558_galleryImage_thumb42.jpg', '2020-12-19 06:16:01', '2020-12-19 06:16:01'),
(33, 'local', 3, 'Toys', NULL, 0, 'images/20201219121736_galleryImage_big19.jpg', 'images/20201219121736_galleryImage_thumb8.jpg', '2020-12-19 06:17:37', '2020-12-19 06:17:37'),
(34, 'local', 3, 'Toys', NULL, 0, 'images/20201219121737_galleryImage_big15.jpg', 'images/20201219121737_galleryImage_thumb44.jpg', '2020-12-19 06:17:39', '2020-12-19 06:17:39'),
(35, 'local', 3, 'Toys', NULL, 0, 'images/20201219121739_galleryImage_big42.jpg', 'images/20201219121739_galleryImage_thumb25.jpg', '2020-12-19 06:17:42', '2020-12-19 06:17:42'),
(36, 'local', 3, 'Toys', NULL, 0, 'images/20201219121742_galleryImage_big29.jpg', 'images/20201219121742_galleryImage_thumb30.jpg', '2020-12-19 06:17:44', '2020-12-19 06:17:44')");

        Model::unguard();

        // $this->call("OthersTableSeeder");
    }
}
