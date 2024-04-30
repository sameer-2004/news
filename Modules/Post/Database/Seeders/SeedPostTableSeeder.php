<?php

namespace Modules\Post\Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;
use IlluminateAgnostic\Str\Support\Str;
use Modules\Post\Entities\Post;
use Faker\Factory as Faker;
use DB;

class SeedPostTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Cache::Flush();

        if (strtolower(\Config::get('app.demo_mode')) == 'yes'):

            DB::statement("INSERT INTO posts (`id`, `title`, `slug`, `content`, `language`, `user_id`, `category_id`, `sub_category_id`, `post_type`, `submitted`, `image_id`, `visibility`, `auth_required`, `slider`, `slider_order`, `featured`, `featured_order`, `breaking`, `breaking_order`, `recommended`, `recommended_order`, `editor_picks`, `editor_picks_order`, `scheduled`, `meta_title`, `meta_keywords`, `meta_description`, `tags`, `scheduled_date`, `layout`, `video_id`, `video_url_type`, `video_url`, `video_thumbnail_id`, `status`, `total_hit`, `contents`, `read_more_link`, `created_at`, `updated_at`) VALUES
    (1, 'Apollo 8: Earthrise', 'apollo-8:-earthrise', 'This iconic picture shows Earth peeking out from beyond the lunar surface as the first crewed spacecraft circumnavigated the Moon.', 'en', 1, 4, 4, 'article', 0, 52, 1, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, NULL, 'rss news,  nasa, nasa news rss', 'Nasa RSS News Importing', 'nasa,rss,nasa-rss', NULL, 'default', NULL, NULL, NULL, NULL, 1, 0, NULL, 'http://www.nasa.gov/image-feature/apollo-8-earthrise', '2020-12-22 21:11:00', '2020-12-19 06:50:06'),
    (2, 'Stellar Snowflake Cluster', 'stellar-snowflake-cluster', 'The newly revealed infant stars appear as pink and red specks toward the center and appear to have formed in regularly spaced intervals along linear structures.', 'en', 1, 4, 4, 'article', 0, 53, 1, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, NULL, 'rss news,  nasa, nasa news rss', 'Nasa RSS News Importing', 'nasa,rss,nasa-rss', NULL, 'default', NULL, NULL, NULL, NULL, 1, 0, NULL, 'http://www.nasa.gov/multimedia/imagegallery/image_feature_476.html', '2020-12-22 01:04:00', '2020-12-19 06:50:55'),
    (3, 'Stellar Jewel Box', 'stellar-jewel-box', 'Thousands of sparkling young stars are nestled within the giant nebula NGC 3603, one of the most massive young star clusters in the Milky Way Galaxy. NGC 3603, a prominent star-forming region in the Carina spiral arm of the Milky Way about 20,000 light-years away, reveals stages in the life cycle of stars.', 'en', 1, 4, 4, 'article', 0, 54, 1, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, NULL, 'rss news,  nasa, nasa news rss', 'Nasa RSS News Importing', 'nasa,rss,nasa-rss', NULL, 'default', NULL, NULL, NULL, NULL, 1, 0, NULL, 'http://www.nasa.gov/multimedia/imagegallery/image_feature_929.html', '2020-12-20 23:40:00', '2020-12-19 06:51:16'),
    (4, 'A Great Conjunction Draws Near', 'a-great-conjunction-draws-near', 'The Moon, left, Saturn, upper right, and Jupiter, lower right, are seen after sunset with the Washington Monument, Thurs. Dec. 17, 2020, in Washington.', 'en', 1, 4, 4, 'article', 0, 55, 1, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, NULL, 'rss news,  nasa, nasa news rss', 'Nasa RSS News Importing', 'nasa,rss,nasa-rss', NULL, 'default', NULL, NULL, NULL, NULL, 1, 0, NULL, 'http://www.nasa.gov/image-feature/a-great-conjunction-draws-near', '2020-12-18 03:36:00', '2020-12-19 06:51:47'),
    (5, 'Entering the Martian Atmosphere with the Perseverance Rover', 'entering-the-martian-atmosphere-with-the-perseverance-rover', 'With its heat shield facing the planet, NASA’s Perseverance rover begins its descent through the Martian atmosphere in this illustration.', 'en', 1, 4, 4, 'article', 0, 56, 1, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, NULL, 'rss news,  nasa, nasa news rss', 'Nasa RSS News Importing', 'nasa,rss,nasa-rss', NULL, 'default', NULL, NULL, NULL, NULL, 1, 0, NULL, 'http://www.nasa.gov/image-feature/jpl/entering-the-martian-atmosphere-with-the-perseverance-rover', '2020-12-17 00:29:00', '2020-12-19 06:51:55'),
    (6, 'The Into the Impossible Podcast Honors Arthur C. Clarke', 'the-into-the-impossible-podcast-honors-arthur-c.-clarke', 'Physicist Brian Keating, codirector of the Arthur C. Clarke Center for the Human Imagination, started the show to continue the center\'s mission of bringing together the world\'s top thinkers.', 'en', 1, 4, 5, 'article', 0, 57, 1, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, NULL, 'wired news,  wired , wired news rss', 'Wired RSS News Importing', 'wired ,wired-rss,rss', NULL, 'style_2', NULL, NULL, NULL, NULL, 1, 0, NULL, 'https://www.wired.com/2020/12/geeks-guide-into-the-impossible', '2020-12-18 11:00:00', '2020-12-19 06:52:05'),
    (7, 'HBO Max Is Now on Roku. Thanks, Wonder Woman!', 'hbo-max-is-now-on-roku.-thanks,-wonder-woman!', 'After a long wait—and a lot of frustration—the streaming service is finally available on Roku devices. Just in time to watch Wonder Woman 1984.', 'en', 1, 4, 5, 'article', 0, 58, 1, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, NULL, 'wired news,  wired , wired news rss', 'Wired RSS News Importing', 'wired ,wired-rss,rss', NULL, 'style_2', NULL, NULL, NULL, NULL, 1, 0, NULL, 'https://www.wired.com/story/hbo-max-roku-wonder-woman-1984', '2020-12-18 08:00:00', '2020-12-19 06:52:14'),
    (8, 'Cyberpunk 2077 Revives the Dystopian Fears of the 1980s', 'cyberpunk-2077-revives-the-dystopian-fears-of-the-1980s', 'Putting aside the game\'s bugs and issues, the underlying themes speak to the fears and worries of a time gone by—not that we\'ve moved past them all.', 'en', 1, 4, 5, 'article', 0, 59, 1, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, NULL, 'wired news,  wired , wired news rss', 'Wired RSS News Importing', 'wired ,wired-rss,rss', NULL, 'style_2', NULL, NULL, NULL, NULL, 1, 0, NULL, 'https://www.wired.com/story/cyberpunk-2077-review-1980s-nostalgia', '2020-12-18 06:00:00', '2020-12-19 06:52:21'),
    (9, 'The Future of Work: ‘ars longa’ by Tade Thompson', 'the-future-of-work:-‘ars-longa’-by-tade-thompson', '“It’s all well and good for an android to take a position and shut down motor functions. There’s no art in that. I want the old ways. That’s why I’m on Earth.”', 'en', 1, 4, 5, 'article', 0, 60, 1, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, NULL, 'wired news,  wired , wired news rss', 'Wired RSS News Importing', 'wired ,wired-rss,rss', NULL, 'style_2', NULL, NULL, NULL, NULL, 1, 0, NULL, 'https://www.wired.com/story/future-of-work-ars-longa-tade-thompson', '2020-12-18 06:00:00', '2020-12-19 06:55:33'),
    (10, 'The Best TikToks of 2020', 'the-best-tiktoks-of-2020', 'The social media platform, which gained enormous popularity this year, served as a mirror of these often dystopian times.', 'en', 1, 4, 5, 'article', 0, 61, 1, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, NULL, 'wired news,  wired , wired news rss', 'Wired RSS News Importing', 'wired ,wired-rss,rss', NULL, 'style_2', NULL, NULL, NULL, NULL, 1, 0, NULL, 'https://www.wired.com/story/best-tiktoks-2020', '2020-12-17 06:00:00', '2020-12-19 06:55:44'),
    (11, 'Outbreaks prompt alerts for banana disease lurking in backyards', 'outbreaks-prompt-alerts-for-banana-disease-lurking-in-backyards', '\n              \n              <p>Home fruit growers in southern Queensland and Northern NSW are urged to be on guard for signs of the world\'s most damaging banana disease after a rapid rise in infections. </p>\n              \n            ', 'en', 1, 4, 6, 'article', 0, 62, 1, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, NULL, 'abc news,  abc, abc news rss', 'ABC News RSS Importing', 'abc-news,abc,abc-news-rss,news', NULL, 'style_3', NULL, NULL, NULL, NULL, 1, 0, NULL, 'https://www.abc.net.au/news/rural/2020-10-30/bunchy-top-virus-disease-bananas/12829796', '2020-10-30 08:36:49', '2020-12-19 06:55:57'),
    (12, 'Race on to save $600m Queensland banana industry', 'race-on-to-save-$600m-queensland-banana-industry', '\n              \n              <p>A new state-of-the art quarantine facility on Queensland\'s Sunshine Coast is fast-tracking imports of alternative banana varieties in a bid to tackle disease threatening the banana industry.</p>\n              \n            ', 'en', 1, 4, 6, 'article', 0, 63, 1, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, NULL, 'abc news,  abc, abc news rss', 'ABC News RSS Importing', 'abc-news,abc,abc-news-rss,news', NULL, 'style_3', NULL, NULL, NULL, NULL, 1, 0, NULL, 'https://www.abc.net.au/news/rural/2020-05-19/banana-quarantine-facility-panama-disease-tropical-race-4/12259994', '2020-05-19 01:05:57', '2020-12-19 06:56:04'),
    (13, 'Devastating Panama disease detected in heart of banana-growing region', 'devastating-panama-disease-detected-in-heart-of-banana-growing-region', '\n              \n              <p>The fungal disease with the potential to wipe out whole banana crops is detected on a farm in Far North Queensland, the centre of Australia\'s $580 million industry.</p>\n              \n            ', 'en', 1, 4, 6, 'article', 0, 64, 1, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, NULL, 'abc news,  abc, abc news rss', 'ABC News RSS Importing', 'abc-news,abc,abc-news-rss,news', NULL, 'style_3', NULL, NULL, NULL, NULL, 1, 0, NULL, 'https://www.abc.net.au/news/2020-02-05/panama-fungal-disease-detected-north-queensland-banana-farm/11932314', '2020-02-05 09:18:35', '2020-12-19 06:56:12'),
    (14, 'Like \'Armageddon\': Farmers reeling in aftermath of a terrifying firestorm they had no hope of beating', 'like-\'armageddon\':-farmers-reeling-in-aftermath-of-a-terrifying-firestorm-they-had-no-hope-of-beating', '\n              \n              <p>With thousands of hectares of farmland burnt, countless head of cattle dead and tonnes of crops scorched, bushfires have brought chaos and untold suffering to farmers in northern New South Wales.</p>\n              \n            ', 'en', 1, 4, 6, 'article', 0, 65, 1, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, NULL, 'abc news,  abc, abc news rss', 'ABC News RSS Importing', 'abc-news,abc,abc-news-rss,news', NULL, 'style_3', NULL, NULL, NULL, NULL, 1, 2, NULL, 'https://www.abc.net.au/news/rural/2019-11-16/drought-affected-farmers-dealt-another-blow-with-fires/11704476', '2019-11-16 04:57:07', '2020-12-19 06:56:17'),
    (15, 'Why are flying foxes turning up in strange places?', 'why-are-flying-foxes-turning-up-in-strange-places', '\n              \n              <p>As their usual food sources dry up, flying foxes are searching further afield for shelter and something to eat.</p>\n              \n            ', 'en', 1, 4, 6, 'article', 0, 66, 1, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, NULL, 'abc news,  abc, abc news rss', 'ABC News RSS Importing', 'abc-news,abc,abc-news-rss,news', NULL, 'style_3', NULL, NULL, NULL, NULL, 1, 1, NULL, 'https://www.abc.net.au/news/2019-10-28/flying-foxes-turn-up-in-strange-places-on-nsw-mid-north-coast/11644260', '2019-10-28 00:22:10', '2020-12-19 06:56:28')");


            DB::statement('SET FOREIGN_KEY_CHECKS=0;');
            $faker = Faker::create('en_US');

            $moreContents = array(  1 =>array('text' =>array('text' => 'However, I\'ve got to see if there were no arches left, and all dripping wet, cross, and uncomfortable. The moment Alice appeared, she was now more than three.\' \'Your hair wants cutting,\' said the King. (The jury all looked puzzled.) \'He must have a prize herself, you know,\' said Alice, always ready to agree to everything that was linked into hers began to repeat it, but her voice sounded hoarse and strange, and the choking of the wood--(she considered him to you, Though they were gardeners, or soldiers, or courtiers, or three times over to the little door, had vanished completely.',),),
                2 =>array('image' => array('image_id' => '50',),),
                3 =>array('youtube-embed' =>array('youtube' => '<iframe width="560" height="315" src="https://www.youtube.com/embed/aqz-KE-bpKQ" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>',),),
                4 =>array('image-text' =>array('image_id' => '45','text' => '\'You might just as I used--and I don\'t take this young lady to see it pop down a very short time the Queen said severely \'Who is it I can\'t put it into one of the house, quite forgetting in the air. She did not come the same size: to be otherwise."\' \'I think I must be off, then!\' said the Mock Turtle went on.',),),
                5 =>array('twitter-embed' =>array('twitter' => '<blockquote class="twitter-tweet"><p lang="en" dir="ltr">Sunsets don&#39;t get much better than this one over <a href="https://twitter.com/GrandTetonNPS?ref_src=twsrc%5Etfw">@GrandTetonNPS</a>. <a href="https://twitter.com/hashtag/nature?src=hash&amp;ref_src=twsrc%5Etfw">#nature</a> <a href="https://twitter.com/hashtag/sunset?src=hash&amp;ref_src=twsrc%5Etfw">#sunset</a> <a href="http://t.co/YuKy2rcjyU">pic.twitter.com/YuKy2rcjyU</a></p>&mdash; US Department of the Interior (@Interior) <a href="https://twitter.com/Interior/status/463440424141459456?ref_src=twsrc%5Etfw">May 5, 2014</a></blockquote> <script async src="https://platform.twitter.com/widgets.js" charset="utf-8"></script>',),),
                6 =>array('text-image' =>array('text' => '\'You might just as I used--and I don\'t take this young lady to see it pop down a very short time the Queen said severely \'Who is it I can\'t put it into one of the house, quite forgetting in the air. She did not come the same size: to be otherwise."\' \'I think I must be off, then!\' said the Mock Turtle went on. \'Would you like to be sure.', 'image_id' => '33',),),
                7 =>array('text-image-text' =>array('text_1' => '\'You might just as I used--and I don\'t take this young lady to see it pop down a very short time the Queen said severely \'Who is it I can\'t put it into one.', 'image_id' => '21', 'text_2' => '\'I think I must be off, then!\' said the Mock Turtle went on. \'Would you like to be sure, this generally happens when one eats cake.',),),
                8 =>array('ads' =>array('ads' => '1',),),
                9 => array ('video' =>array ('video_id' => '5','video_url_type' => NULL,'video_url' => NULL,'video_thumbnail_id' => '21',),),
                10 =>array('vimeo-embed' =>array('vimeo' => '<iframe width="100%" height="550" src="https://player.vimeo.com/video/403530213" frameborder="0" allow="autoplay; encrypted-media" allowfullscreen=""></iframe>',),),
            );
            $image = 0;

            for ($i = 1; $i <= 30; $i++) {

                if (($i <= 4) or ($i > 10 and $i <= 14) or ($i > 20 and $i <= 24)):
                    $type = 'article';
                elseif (($i > 4 and $i <= 7) or ($i > 14 and $i <= 17) or ($i >20 and $i<=27)):
                    $type = 'video';
                    $layout = 'style_3';
                    if($i > 4 and $i <= 6):
                        $video_id = 1;
                        $video_thumbnail_id = 2;
                    elseif($i > 14 and $i <= 16):
                        $video_id = 2;
                        $video_thumbnail_id = 3;
                    elseif($i >20 and $i<=26):
                        $video_id = 3;
                        $video_thumbnail_id = 4;
                    else:
                        $video_id = 4;
                        $video_thumbnail_id = 5;
                    endif;

                else:
                    $type = 'audio';
                    $layout = 'style_2';
                endif;

                if ($i <= 10):
                    $category = 1;
                    $subCategory = 1;
                elseif ($i <= 20):
                    $category = 2;
                    $subCategory = 2;
                else:
                    $category = 3;
                    $subCategory = 3;
                endif;

                Post::create([
                    'title' => htmlspecialchars($faker->realText(120)),
                    'slug' => htmlspecialchars($this->make_slug($faker->sentence . '-' . $i)),
                    'content' => htmlspecialchars($faker->realText(2000)),
                    'language' => 'en',
                    'user_id' => '1',
                    'category_id' => $category,
                    'sub_category_id' => $subCategory,
                    'post_type' => $type,
                    'visibility' => '1',
                    'status' => '1',
                    'slider' => ($i >= 30 and $i < 40) ? 1 : 0,
                    'tags' => 'politics,world',
                    'featured' => ($i >= 15 and $i < 25) ? 1 : 0,
                    'breaking' => ($i >= 20 and $i < 30) ? 1 : 0,
                    'recommended' => ($i >= 25 and $i < 35) ? 1 : 0,
                    'editor_picks' => ($i >= 30 and $i < 40) ? 1 : 0,
                    'image_id' => ++$image,
                    'layout'    => $layout ?? 'default',
                    'video_id' => $video_id ?? '',
                    'video_thumbnail_id' => $video_thumbnail_id ?? '',
                    'contents' => $moreContents,
                ]);

            }

            // for arabic start

            $faker = Faker::create('ar_JO');
            $image = 20;

            for ($i = 1; $i <= 30; $i++) {

                if (($i <= 4) or ($i > 10 and $i <= 14) or ($i > 20 and $i <= 24)):
                    $type = 'article';
                elseif (($i > 4 and $i <= 7) or ($i > 14 and $i <= 17) or ($i >20 and $i<=27)):
                    $type = 'video';
                    $layout = 'style_3';
                    if($i > 4 and $i <= 6):
                        $video_id = 1;
                        $video_thumbnail_id = 2;
                    elseif($i > 14 and $i <= 16):
                        $video_id = 2;
                        $video_thumbnail_id = 3;
                    elseif($i >20 and $i<=26):
                        $video_id = 3;
                        $video_thumbnail_id = 4;
                    else:
                        $video_id = 4;
                        $video_thumbnail_id = 5;
                    endif;

                else:
                    $type = 'audio';
                    $layout = 'style_2';
                endif;

                if ($i <= 10):
                    $category = 5;
                    $subCategory = 7;
                else:
                    $category = 6;
                    $subCategory = 8;
                endif;

                Post::create([
                    'title' => htmlspecialchars($faker->realText(120)),
                    'slug' => htmlspecialchars($this->make_slug($faker->realText(60) . '-' . $i)),
                    'content' => $faker->realText(2000),
                    'language' => 'ar',
                    'user_id' => '1',
                    'category_id' => $category,
                    'sub_category_id' => $subCategory,
                    'post_type' => $type,
                    'visibility' => '1',
                    'status' => '1',
                    'slider' => ($i >= 30 and $i < 40) ? 1 : 0,
                    'tags' => 'سياسة ,العالمية',
                    'featured' => ($i >= 15 and $i < 25) ? 1 : 0,
                    'breaking' => ($i >= 20 and $i < 30) ? 1 : 0,
                    'recommended' => ($i >= 25 and $i < 35) ? 1 : 0,
                    'editor_picks' => ($i >= 30 and $i < 40) ? 1 : 0,
                    'image_id' => ++$image,
                    'layout'    => $layout ?? 'default',
                    'video_id' => $video_id ?? '',
                    'video_thumbnail_id' => $video_thumbnail_id ?? '',
                ]);

            }

        else:
            DB::statement('SET FOREIGN_KEY_CHECKS=0;');
            $faker = Faker::create('en_US');
            for($i = 1; $i <= 2; $i++) {
                $slider = 0;

                if($i == 1):
                    $slider = 1;
                    $type                = 'article';
                else:
                    $type                = 'video';
                    $video_url_type      = 'mp4_url';
                    $video_url           = 'http://www.caminandes.com/download/03_caminandes_llamigos_1080p.mp4';
                endif;

                if($i <= 3 ):
                    $category           = 1;
                    $subCategory        = 1;
                else:
                    $category           = 2;
                    $subCategory        = 2;
                endif;

                Post::create([
                    'title'             => htmlspecialchars("Sample $type post"),
                    'slug'              => htmlspecialchars($this->make_slug("sample $type post")),
                    'content'           => htmlspecialchars("Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum. Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum. Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum."),
                    'language'          => 'en',
                    'user_id'           => '1',
                    'category_id'       => $category,
                    'sub_category_id'   => $subCategory,
                    'post_type'         => $type,
                    'visibility'        => '1',
                    'status'            => '1',
                    'slider'            => $slider,
                    'tags'              => 'politics,world',
                    'featured'          => '1',
                    'breaking'          => '1',
                    'recommended'       => '1',
                    'editor_picks'      => '1',
                    'video_url_type'    => $video_url_type ?? '',
                    'video_url'         => $video_url ?? ''
                ]);

            }
        endif;

        Model::unguard();

        // $this->call("OthersTableSeeder");
    }


    private function make_slug($string, $delimiter = '-') {

        $string = preg_replace("/[~`{}.'\"\!\@\#\$\%\^\&\*\(\)\_\=\+\/\?\>\<\,\[\]\:\;\|\\\]/", "", $string);

        $string = preg_replace("/[\/_|+ -]+/", $delimiter, $string);
        $result = mb_strtolower($string);

        if ($result):
            return $result;
        else:
            return $string;
        endif;
    }
}
