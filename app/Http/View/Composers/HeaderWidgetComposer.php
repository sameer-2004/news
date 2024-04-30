<?php

namespace App\Http\View\Composers;

use App\Services\HeaderWidgetService;
use Illuminate\Support\Facades\Cache;
use Illuminate\View\View;

class HeaderWidgetComposer
{
    protected $widgetService;

    public function __construct(HeaderWidgetService $widgetService)
    {
        $this->widgetService = $widgetService;
    }

    public function compose(View $view)
    {
        $widgets = Cache::rememberForever('headerWidgets', function (){
                        return $this->widgetService->getWidgetDetails();
                    });
        $view->with('widgets', $widgets);
    }
}
