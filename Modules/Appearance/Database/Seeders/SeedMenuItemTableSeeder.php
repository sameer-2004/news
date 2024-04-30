<?php

namespace Modules\Appearance\Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;
use Modules\Appearance\Entities\MenuItem;
use DB;

class SeedMenuItemTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {

        DB::statement('SET FOREIGN_KEY_CHECKS=0;');

        if ( strtolower(\Config::get('app.demo_mode')) == 'yes'):
            DB::statement("INSERT INTO `menu_item` (`id`, `label`, `language`, `menu_id`, `is_mega_menu`, `order`, `parent`, `source`, `url`, `page_id`, `category_id`, `sub_category_id`, `post_id`, `status`, `new_teb`, `created_at`, `updated_at`) VALUES
            (1, 'Home', 'en', 1, 'no', 1, NULL, 'custom', '#', NULL, NULL, NULL, NULL, 1, 0, '2020-10-14 11:26:41', '2020-12-19 03:45:20'),
            (2, 'Life Style', 'en', 1, 'tab', 2, NULL, 'custom', '', NULL, NULL, NULL, NULL, 1, 0, '2020-10-14 11:33:29', '2020-12-19 03:45:20'),
            (3, 'World', 'en', 1, 'no', 3, 2, 'category', NULL, NULL, 1, NULL, NULL, 1, 0, '2020-10-14 11:33:38', '2020-12-19 03:45:20'),
            (4, 'Science', 'en', 1, 'no', 4, 2, 'category', NULL, NULL, 2, NULL, NULL, 1, 0, '2020-10-14 11:33:38', '2020-12-19 03:45:20'),
            (6, 'Contact Us', 'en', 1, 'no', 11, 32, 'page', NULL, 1, NULL, NULL, NULL, 1, 0, '2020-10-14 11:34:07', '2020-12-19 10:35:31'),
            (16, 'About us', 'en', 1, 'no', 12, 32, 'page', NULL, 2, NULL, NULL, NULL, 1, 0, '2020-10-14 11:42:29', '2020-12-19 03:45:40'),
            (17, 'الصفحة الرئيسية', 'ar', 1, 'no', 1, NULL, 'custom', '#', NULL, NULL, NULL, NULL, 1, 0, '2020-10-16 20:45:53', '2020-10-16 20:53:35'),
            (18, 'أسلوب الحياة', 'ar', 1, 'tab', 2, NULL, 'custom', '#', NULL, NULL, NULL, NULL, 1, 0, '2020-10-16 20:46:23', '2020-10-16 20:53:35'),
            (19, 'العالمية', 'ar', 1, 'no', 3, 18, 'category', NULL, NULL, 4, NULL, NULL, 1, 0, '2020-10-16 20:46:49', '2020-10-16 20:53:35'),
            (20, 'علم', 'ar', 1, 'no', 4, 18, 'category', NULL, NULL, 5, NULL, NULL, 1, 0, '2020-10-16 20:46:49', '2020-10-16 20:53:35'),
            (21, 'أشرطة فيديو', 'ar', 1, 'category', 5, NULL, 'custom', '#', NULL, NULL, NULL, NULL, 1, 0, '2020-10-16 20:48:26', '2020-10-16 20:53:35'),
            (22, 'السفل، طالباً للنزول. وكذلك الدخان في صعوده، لا ينثني إلا أن يكون السواد مثلاً حلواً أو حامضاً. لكنا، مع ذلك، لا نخيلك.', 'ar', 1, 'no', 11, 29, 'post', NULL, NULL, NULL, NULL, 40, 1, 0, '2020-10-16 20:50:14', '2020-10-16 20:53:35'),
            (23, 'ألم نقدم إليك إن مجال العبارة هنا ضيق، وان الألفاظ على كل حال قصير المدة. واتخذ من الصياصي البقر الوحشية شبه الاسنة،.', 'ar', 1, 'no', 12, 29, 'post', NULL, NULL, NULL, NULL, 39, 1, 0, '2020-10-16 20:50:14', '2020-10-16 20:53:35'),
            (24, 'التي بدأ بالشق منها، فقال في نفسه: إن كان لهذا العضو من الجهة اليمنى والآخر من الجهة المقابلة للقراءة الثانية، نفاخة.', 'ar', 1, 'no', 13, 29, 'post', NULL, NULL, NULL, NULL, 38, 1, 0, '2020-10-16 20:50:14', '2020-10-16 20:53:35'),
            (25, 'القرب منه والتشبه به. فرأى أن حقيقة وجود كل واحد من هذه الثلاثة قد يقال له قلب ولكن لا سبيل إلى خطورة على القلب، ولا.', 'ar', 1, 'no', 7, 28, 'post', NULL, NULL, NULL, NULL, 37, 1, 0, '2020-10-16 20:50:14', '2020-10-16 20:53:35'),
            (26, 'وأنعم النظر إليها، فرأى هولاً عظيماً وخطباً جسيماً، وخلقاً حثيثاً، وأحكاماً بليغة، وتسوية ونفخاً وإنشاء ونسخاً. فما هو.', 'ar', 1, 'no', 8, 28, 'post', NULL, NULL, NULL, NULL, 36, 1, 0, '2020-10-16 20:50:14', '2020-10-16 20:53:35'),
            (27, 'الشمس أعظم من تألم من يفقد شمه، إذ الأشياء التي يدركها البصر أتم وأحسن من التي يدركها البصر أتم وأحسن من التي يدركها.', 'ar', 1, 'no', 9, 28, 'post', NULL, NULL, NULL, NULL, 35, 1, 0, '2020-10-16 20:50:14', '2020-10-16 20:53:35'),
            (28, 'رياضات', 'ar', 1, 'no', 6, 21, 'custom', '#', NULL, NULL, NULL, NULL, 1, 0, '2020-10-16 20:50:42', '2020-10-16 20:53:35'),
            (29, 'مرض فيروس كورونا', 'ar', 1, 'no', 10, 21, 'custom', NULL, NULL, NULL, NULL, NULL, 1, 0, '2020-10-16 20:50:56', '2020-10-16 20:53:35'),
            (30, 'اتصل بنا', 'ar', 1, 'no', 15, NULL, 'page', NULL, 3, NULL, NULL, NULL, 1, 0, '2020-10-16 20:54:01', '2020-10-16 20:54:24'),
            (31, 'معلومات عنا', 'ar', 1, 'no', 14, NULL, 'page', NULL, 4, NULL, NULL, NULL, 1, 0, '2020-10-16 20:54:01', '2020-10-16 20:54:24'),
            (32, 'Pages', 'en', 1, 'no', 10, NULL, 'custom', '#', NULL, NULL, NULL, NULL, 1, 0, '2020-12-19 03:05:24', '2020-12-19 10:35:31'),
            (33, 'RSS News', 'en', 1, 'no', 5, NULL, 'category', NULL, NULL, 4, NULL, NULL, 1, 0, '2020-12-19 03:06:18', '2020-12-19 03:45:20'),
            (34, 'Nasa', 'en', 1, 'no', 6, 33, 'sub-category', NULL, NULL, NULL, 4, NULL, 1, 0, '2020-12-19 03:06:40', '2020-12-19 03:45:20'),
            (35, 'Wired', 'en', 1, 'no', 7, 33, 'sub-category', NULL, NULL, NULL, 5, NULL, 1, 0, '2020-12-19 03:06:40', '2020-12-19 03:45:20'),
            (36, 'ABC News', 'en', 1, 'no', 8, 33, 'sub-category', NULL, NULL, NULL, 6, NULL, 1, 0, '2020-12-19 03:06:40', '2020-12-19 03:45:20'),
            (38, 'gallery', 'en', 1, 'no', 9, NULL, 'page', NULL, NULL, NULL, NULL, NULL, 1, 0, '2020-12-19 03:37:06', '2020-12-19 10:35:31')");

        else:
            DB::statement("INSERT INTO `menu_item` (`id`, `label`, `language`, `menu_id`, `is_mega_menu`, `order`, `parent`, `source`, `url`, `page_id`, `category_id`, `sub_category_id`, `post_id`, `status`, `new_teb`, `created_at`, `updated_at`) VALUES
            (1, 'Home', 'en', 1, 'no', 1, NULL, 'custom', '#', NULL, NULL, NULL, NULL, 1, 0, '2020-10-14 11:26:41', '2020-12-19 03:45:20'),
            (2, 'Contact Us', 'en', 1, 'no', 11, NULL, 'page', NULL, 1, NULL, NULL, NULL, 1, 0, '2020-10-14 11:34:07', '2020-12-19 10:35:31'),
            (3, 'About us', 'en', 1, 'no', 12, NULL, 'page', NULL, 2, NULL, NULL, NULL, 1, 0, '2020-10-14 11:42:29', '2020-12-19 03:45:40')");
        endif;

        Model::unguard();

        // $this->call("OthersTableSeeder");
    }
}
