<?php

namespace Modules\Post\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Cache;
use Modules\Language\Entities\Language;
use Modules\Post\Entities\Category;
use Modules\Post\Entities\SubCategory;
use Validator;
use DB;

class CategoryController extends Controller
{
    public function categories()
    {
        $categories     = Category::orderBy('id', 'ASC')->paginate(10);
        $activeLang     = Language::where('status', 'active')->orderBy('name', 'ASC')->get();

        return view('post::categories', compact('activeLang', 'categories'));
    }

    public function saveNewCategory(Request $request)
    {
        if (strtolower(\Config::get('app.demo_mode')) == 'yes'):
            return redirect()->back()->with('error', __('You are not allowed to add/modify in demo mode.'));
        endif;
        Validator::make($request->all(), [
            'category_name' => 'required|unique:categories|min:2|max:40',
            'slug'          => 'nullable|min:2|unique:categories|max:30|regex:/^\S*$/u',
            'language'      => 'required'
        ])->validate();

        $category                   = new Category();

        $category->category_name    = $request->category_name;
        $category->language         = $request->language;
        $category->is_featured      = $request->is_featured;

        if ($request->slug != null) :
            $category->slug = $request->slug;
        else :
            $category->slug = $this->make_slug($request->category_name);
        endif;

        $category->meta_description     = $request->meta_description;
        $category->meta_keywords        = $request->meta_keywords;
        $category->order                = $request->order;
       // $category->show_on_menu         = $request->show_on_menu;
//        $category->block_style          = $request->block_style;

        $category->save();

        Cache::Flush();

        return redirect()->back()->with('success', __('successfully_added'));
    }

    public function editCategory($id)
    {
        $category       = Category::find($id);
        $activeLang     = Language::where('status', 'active')->orderBy('name', 'ASC')->get();

        return view('post::edit_category', compact('category', 'activeLang'));
    }

    public function updateCategory(Request $request)
    {
        if (strtolower(\Config::get('app.demo_mode')) == 'yes'):
            return redirect()->back()->with('error', __('You are not allowed to add/modify in demo mode.'));
        endif;
        Validator::make($request->all(), [
            'category_name'     => 'required|min:2|max:40|unique:categories,category_name,' . $request->category_id,
            'slug'              => 'nullable|min:2|max:30|regex:/^\S*$/u|unique:categories,slug,' . $request->category_id,
            'language'          => 'required'
        ])->validate();

        $category                   = Category::find($request->category_id);

        $category->category_name    = $request->category_name;
        $category->language         = $request->language;
        $category->is_featured      = $request->is_featured;

        if ($request->slug != null) :
            $category->slug     = $request->slug;
        else :
            $category->slug     = $this->make_slug($request->category_name);
        endif;

        $category->meta_description = $request->meta_description;
        $category->meta_keywords    = $request->meta_keywords;
        $category->order            = $request->order;
 //       $category->show_on_menu     = $request->show_on_menu;
//        $category->block_style      = $request->block_style;

        $category->save();

        Cache::Flush();

        return redirect()->route('categories')->with('success', __('successfully_updated'));
    }

    public function subCategories()
    {
        $subCategories          = SubCategory::with('category')->orderBy('id', 'ASC')->paginate(10);
        $categories             = Category::where('language', \App::getLocale() ?? settingHelper('default_language'))->get();
        $activeLang             = Language::where('status', 'active')->orderBy('name', 'ASC')->get();

        return view('post::sub_categories', compact('subCategories', 'activeLang', 'categories'));
    }

    public function subCategoriesAdd(Request $request)
    {
        if (strtolower(\Config::get('app.demo_mode')) == 'yes'):
            return redirect()->back()->with('error', __('You are not allowed to add/modify in demo mode.'));
        endif;
        Validator::make($request->all(), [
            'sub_category_name' => 'required|unique:sub_categories|min:2|max:40',
            'language'          => 'required',
            'slug'              => 'nullable|min:2|unique:categories|max:30|regex:/^\S*$/u',
            'category_id'       => 'required'
        ])->validate();

        $subCategory                    = new SubCategory();

        $subCategory->sub_category_name = $request->sub_category_name;
        $subCategory->language          = $request->language;

        if ($request->slug != null) :
            $subCategory->slug  = $request->slug;
        else :
            $subCategory->slug  = $this->make_slug($request->sub_category_name);
        endif;

        $subCategory->meta_description  = $request->meta_description;
        $subCategory->meta_keywords     = $request->meta_keywords;
       // $subCategory->show_on_menu      = $request->show_on_menu;
        $subCategory->category_id       = $request->category_id;

        $subCategory->save();

        Cache::Flush();

        return redirect()->back()->with('success', __('successfully_added'));
    }

    public function editSubCategory($id)
    {
        $subCategory    = SubCategory::find($id);
        $categories     = Category::all()->where('language', \App::getLocale() ?? settingHelper('default_language'));
        $activeLang     = Language::where('status', 'active')->orderBy('name', 'ASC')->get();

        return view('post::edit_sub_category', compact('subCategory', 'activeLang', 'categories'));
    }

    public function updateSubCategory(Request $request)
    {
        if (strtolower(\Config::get('app.demo_mode')) == 'yes'):
            return redirect()->back()->with('error', __('You are not allowed to add/modify in demo mode.'));
        endif;
        Validator::make($request->all(), [
            'sub_category_name'     => 'required|min:2|max:40|unique:sub_categories,sub_category_name,' . $request->sub_category_id,
            'language'              => 'required',
            'slug'                  => 'nullable|min:2|max:30|regex:/^\S*$/u|unique:sub_categories,slug,' . $request->sub_category_id,
            'category_id'           => 'required'
        ])->validate();

        $subCategory                    = SubCategory::find($request->sub_category_id);
        $subCategory->sub_category_name = $request->sub_category_name;
        $subCategory->language          = $request->language;

        if ($request->slug != null) :
            $subCategory->slug  = $request->slug;
        else :
            $subCategory->slug  = $this->make_slug($request->sub_category_name);
        endif;

        $subCategory->meta_description  = $request->meta_description;
        $subCategory->meta_keywords     = $request->meta_keywords;
     //   $subCategory->show_on_menu      = $request->show_on_menu;
        $subCategory->category_id       = $request->category_id;

        $subCategory->save();

        Cache::Flush();

        return redirect()->route('sub-categories')->with('success', __('successfully_added'));
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
