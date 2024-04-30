<?php

namespace App\Http\View\Composers;

use Illuminate\Support\Facades\Cache;
use Illuminate\View\View;
use Modules\Ads\Entities\AdLocation;

class AdComposer
{
    public function __construct()
    {

    }

    public function compose(View $view)
    {
        $adLocations    = Cache::rememberForever('adLocations', function (){
                            return AdLocation::with('ad.adImage')
                                ->where('status', 1)
                                ->get()
                                ->keyBy('unique_name');
                        });

        $view->with('adLocations', $adLocations);
    }
}
