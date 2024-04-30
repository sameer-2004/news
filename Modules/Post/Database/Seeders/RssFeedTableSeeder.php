<?php

namespace Modules\Post\Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;
use Modules\Post\Entities\RssFeed;
use DB;

class RssFeedTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        if (strtolower(\Config::get('app.demo_mode')) == 'yes'):
            DB::statement("INSERT INTO rss_feeds (id, name, feed_url, language, category_id, sub_category_id, post_limit, auto_update, show_read_more, status, keep_date, meta_keywords, meta_description, tags, scheduled_date, layout, created_at, updated_at) VALUES
            (1, 'Nasa', 'https://www.nasa.gov/rss/dyn/lg_image_of_the_day.rss', 'en', 4, 4, 5, 0, 1, 1, 1, 'rss news,  nasa, nasa news rss', 'Nasa RSS News Importing', 'nasa,rss,nasa-rss', '2020-12-19 05:25:28', 'default', '2020-12-19 05:25:28', '2020-12-19 05:25:28'),
            (2, 'Wired', 'https://www.wired.com/feed/category/culture/latest/rss', 'en', 4, 5, 5, 0, 1, 1, 1, 'wired news,  wired , wired news rss', 'Wired RSS News Importing', 'wired ,wired-rss,rss', '2020-12-19 05:26:48', 'style_2', '2020-12-19 05:26:48', '2020-12-19 05:26:48'),
            (3, 'ABC News', 'https://www.abc.net.au/news/feed/4703630/rss.xml', 'en', 4, 6, 5, 0, 1, 1, 1, 'abc news,  abc, abc news rss', 'ABC News RSS Importing', 'abc-news,abc,abc-news-rss,news', '2020-12-19 05:28:16', 'style_3', '2020-12-19 05:28:16', '2020-12-19 05:28:16')");
        endif;
        Model::unguard();

        // $this->call("OthersTableSeeder");
    }
}
