<?php

namespace Modules\Appearance\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Cache;
use Modules\Appearance\Entities\Menu;
use Modules\Appearance\Entities\MenuItem;
use Modules\Appearance\Entities\MenuLocation;
use Modules\Language\Entities\Language;
use Modules\Page\Entities\Page;
use Modules\Post\Entities\Post;
use Validator;
use Modules\Post\Entities\Category;
use Modules\Post\Entities\SubCategory;

class MenuItemController extends Controller
{
    public function menuItem(){

        $menuLocations      = MenuLocation::with('menu')->get();
        $menus              = Menu::all();
        $selectedMenu       = Menu::first();
        $selectedLanguage   = settingHelper('default_language');
        $categories         = Category::orderBy('id','ASC')->where('language',$selectedLanguage)->get();

        $menuItems          = MenuItem::with(['children'])
                                    ->where('parent', null)
                                    ->where('menu_id', $selectedMenu->id)
                                    ->where('language', $selectedLanguage)
                                    ->orderBy('order', 'asc')
                                    ->get();

        $pages              = Page::where('language',$selectedLanguage)->get();

        $posts              = Post::select('id', 'title')
                                ->orderBy('id', 'desc')->where('language',$selectedLanguage)->get();
        $activeLang         = Language::where('status', 'active')
                                    ->orderBy('name', 'ASC')->get();

        return view('appearance::menu_item',[
                                            'menuItems'         => $menuItems,
                                            'menus'             => $menus,
                                            'selectedMenus'     => $selectedMenu,
                                            'selectedLanguage'  => $selectedLanguage,
                                            'pages'             => $pages,
                                            'menuLocations'     => $menuLocations,
                                            'categories'        => $categories,
                                            'activeLang'        => $activeLang,
                                            'posts'             => $posts
                                        ]);
    }
    public function menuItemSearch(Request $request){
        $menuLocations  = MenuLocation::with('menu')->get();

        $menuItems      = MenuItem::with(['children'])
                            ->where('parent', null)
                            ->where('menu_id', $request->menu_id)
                            ->where('language', $request->language)
                            ->orderBy('order','ASC')
                            ->get();

        $menus              = Menu::all();
        $selectedMenu       = Menu::find($request->menu_id);
        $selectedLanguage   = $request->language;

        $pages              = Page::all()->where('language',$selectedLanguage);
        $posts              = Post::select('id', 'title')->where('language',$selectedLanguage)->orderBy('id', 'desc')->get();
        $activeLang         = Language::where('status', 'active')->orderBy('name', 'ASC')->get();
        $categories         = Category::orderBy('id','ASC')->where('language',$selectedLanguage)->get();

        return view('appearance::menu_item', [
                                            'menuItems' => $menuItems,
                                            'menus' => $menus,
                                            'selectedMenus' => $selectedMenu,
                                            'selectedLanguage' => $selectedLanguage,
                                            'pages' => $pages,
                                            'menuLocations' => $menuLocations,
                                            'categories' => $categories,
                                            'activeLang' => $activeLang,
                                            'posts'=> $posts
                                        ]);
        }

    public function changeMenuOrder(Request $request)
    {
        if (strtolower(\Config::get('app.demo_mode')) == 'yes'):
            $data['status']     = "error";
            $data['message']    = __('You are not allowed to add/modify in demo mode.');

            echo json_encode($data);
            exit();
        endif;

        $data   = \json_decode($request->data);
        $order  = 0;

        foreach ($data as $value):
            $order++;
            $menu_item    = MenuItem::find($value->id);

            if ($menu_item->source == 'category'):
                MenuItem::where('id', $value->id)->update(array('parent' => null, 'order' => $order));
            else:
                MenuItem::where('id', $value->id)->update(array('parent' => null, 'order' => $order));

                if (!empty($value->children)) :
                    foreach ($value->children as $childValue):
                        $child_menu_item    = MenuItem::find($childValue->id);

                        if ($child_menu_item->source == 'category'):
                            MenuItem::where('id', $childValue->id)->update(array('parent' => null, 'order' => $order));
                        else:
                            MenuItem::where('id', $childValue->id)->update(array('parent' => $value->id, 'order' => $order));

                            if (!empty($childValue->children)) :
                                foreach ($childValue->children as $childChildValue) :
                                    MenuItem::where('id', $childChildValue->id)->update(array('parent' => $childValue->id, 'order' => $order));
                                endforeach;
                            endif;
                        endif;
                    endforeach;
                endif;
            endif;
        endforeach;

        Cache::forget('menuDetails');
        Cache::forget('primary_menu');

        $data['status']     = "success";
        $data['message']    = __('successfully_update_menu_arrangement');

        echo json_encode($data);
    }

    public function menuItemSave(Request $request){

        if (strtolower(\Config::get('app.demo_mode')) == 'yes'):
            return redirect()->back()->with('error', 'You are not allowed to add/modify in demo mode.');
        endif;


        Validator::make($request->all(), [
            'source'    => 'required',
            'menu_id'   => 'required'
        ])->validate();

        if ($request->source == 'page') :

            if(!isset($request->page_id)){
                return redirect()->back()->with('error',__('please_select_at_least_one_item'));
            }

            for($i=0; count($request->page_id)>$i; $i++){
                $menuItem           = new MenuItem();

                if($request->page_id[$i] != "gallery"):

                    $page               = Page::find($request->page_id[$i]);
                    $menuItem->label    = $page->title;
                    $menuItem->page_id  = $request->page_id[$i];

                else:

                    $menuItem->label    = 'gallery';

                endif;

                $menuItem->menu_id  = $request->menu_id;
                $menuItem->language = $request->language ?? app()->getLocale();
                $menuItem->source   = $request->source;
                $menuItem->parent   = null;
                $menuItem->status   = 1;
                $menuItem->save();
            }

        elseif ($request->source == 'post') :

            if(!isset($request->post_id)):
                return redirect()->back()->with('error',__('please_select_at_least_one_item'));
            endif;

            for($i=0; count($request->post_id)>$i; $i++):
                $menuItem           = new MenuItem();

                $post               = Post::find($request->post_id[$i]);
                $menuItem->label    = $post->title;
                $menuItem->menu_id  = $request->menu_id;
                $menuItem->language = $request->language ?? app()->getLocale();
                $menuItem->source   = $request->source;
                $menuItem->parent   = null;
                $menuItem->post_id  = $request->post_id[$i];
                $menuItem->status   = 1;

                $menuItem->save();
            endfor;

        elseif ($request->source == 'category') :
            if($request->category_id !=null):
                for($i=0; count($request->category_id)>$i; $i++):

                    $menuItem               = new MenuItem();

                    $category               = Category::find($request->category_id[$i]);
                    $menuItem->label        = $category->category_name;
                    $menuItem->menu_id      = $request->menu_id;
                    $menuItem->language     = $request->language ?? app()->getLocale();
                    $menuItem->source       = $request->source;
                    $menuItem->parent       = null;
                    $menuItem->category_id  = $request->category_id[$i];
                    $menuItem->status       = 1;

                    $menuItem->save();
                endfor;
            else:
                return redirect()->back()->with('error',__('please_select_at_least_one_item'));
            endif;


        elseif ($request->source == 'sub-category') :

            if(!isset($request->sub_category_id)){
                return redirect()->back()->with('error',__('please_select_at_least_one_item'));
            }

                if($request->sub_category_id !=null):
                    for($i=0; count($request->sub_category_id)>$i; $i++):

                        $menuItem               = new MenuItem();
                        $category               = SubCategory::find($request->sub_category_id[$i]);
                        $menuItem->label        = $category->sub_category_name;
                        $menuItem->menu_id      = $request->menu_id;
                        $menuItem->language     = $request->language ?? app()->getLocale();
                        $menuItem->source       = $request->source;
                        $menuItem->parent       = null;
                        $menuItem->sub_category_id  = $request->sub_category_id[$i];
                        $menuItem->status       = 1;

                        $menuItem->save();
                    endfor;
                else:
                    return redirect()->back()->with('error',__('please_select_at_least_one_item'));
                endif;


        else:
            $menuItem                   = new MenuItem();

            $menuItem->label            = $request->label;
            $menuItem->menu_id          = $request->menu_id;
            $menuItem->language         = $request->language ?? app()->getLocale();
            $menuItem->source           = $request->source;
            $menuItem->parent           = null;
            $menuItem->url              = $request->url;
            $menuItem->post_id          = $request->post_id;
            $menuItem->page_id          = $request->page_id;
            $menuItem->status           = 1;

            $menuItem->save();
        endif;

        Cache::forget('menuDetails');
        Cache::forget('primary_menu');

        return redirect()->back()->with('success',__('successfully_added'));
    }

    public function menuItemUpdate(Request $request){

        if (strtolower(\Config::get('app.demo_mode')) == 'yes'):
            return redirect()->back()->with('error', 'You are not allowed to add/modify in demo mode.');
        endif;
        if(blank($request->label) || blank($request->menu_item_id)){

            return redirect()->back()->with('error', __('not_found'));
        }


        $total_item = count($request->label);
        if(isset($request->url)):
            $total_url  = count($request->url);
        endif;

        $url_position   =0;

        $main_menu      = '';
        $sub_menu       = '';
        $order          = 0;

        for($i=0; $i<$total_item; $i++):
            $order++;

            // for making sub menu
            if ($request->menu_lenght[$i] == 1):
                $main_menu  = $request->menu_item_id[$i];
                $sub_menu   = '';
            elseif ($request->menu_lenght[$i] == 2):
                $sub_menu   = $request->menu_item_id[$i];
            endif;

            $menuItem           = MenuItem::find($request->menu_item_id[$i]);

            $menuItem->label    = $request->label[$i];

            $menuItem->new_teb  = $request->new_teb[$i];

            if(isset($request->url)):
                if($menuItem->source == 'custom' && $url_position < $total_url):
                    $menuItem->url          = $request->url[$url_position];
                    $url_position++;
                endif;
            endif;

            if($request->menu_lenght[$i] == 1):
                $menuItem->is_mega_menu     = $request->is_mega_menu[$i];
            endif;

            $menuItem->order    = $order;

             // for making sub menu
            if($request->menu_lenght[$i] == 2):
                $menuItem->parent   = @$main_menu;
            elseif($request->menu_lenght[$i] == 3):
                $menuItem->parent   = @$sub_menu;
            else:
                $menuItem->parent   = null;
            endif;

            $menuItem->save();

        endfor;

        Cache::forget('menuDetails');
        Cache::forget('primary_menu');

        return redirect()->route('menu-item')->with('success', __('successfully_updated'));
    }

    public function menuItemDelete(Request $request){
        if (strtolower(\Config::get('app.demo_mode')) == 'yes'):
            $data['status']     = "error";
            $data['message']    =  __('You are not allowed to add/modify in demo mode.');

            echo json_encode($data);
            exit();
        endif;
        $query= MenuItem::where('id',$request->row_id)->with(['children'])->first();

        if ($query->count() > 0) :
            $query->delete();
            $data['status']     = "success";
            $data['message']    =  __('successfully_deleted');
        else :
            $data['status']     = "error";
            $data['message']    = __('not_found');
        endif;

        Cache::forget('menuDetails');
        Cache::forget('primary_menu');

        echo json_encode($data);
    }

}
