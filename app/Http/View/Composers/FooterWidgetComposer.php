<?php

namespace App\Http\View\Composers;

use App\Services\FooterWidgetService;
use Illuminate\Support\Facades\Cache;
use Illuminate\View\View;

class FooterWidgetComposer
{
    protected $widgetService;

    public function __construct(FooterWidgetService $widgetService)
    {
        $this->widgetService = $widgetService;
    }

    public function compose(View $view)
    {
        $widgets = Cache::rememberForever('$footerWidgets', function (){
                        return $this->widgetService->getWidgetDetails();
                    });
        $view->with('widgets', $widgets);
    }
}
