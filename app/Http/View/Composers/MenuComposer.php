<?php

namespace App\Http\View\Composers;

use App\Services\MenuService;
use App\Services\WidgetService;
use Illuminate\Support\Facades\Cache;
use Illuminate\View\View;

class MenuComposer
{
    protected $menuService;

    public function __construct(MenuService $menuService)
    {
        $this->menuService  = $menuService;
    }

    public function compose(View $view)
    {
        $menuDetails = Cache::rememberForever('menuDetails' , function (){
                           return $this->menuService->getMenuDetails();
                        });
        $view->with('menuDetails', $menuDetails);
    }
}
