<?php

namespace Modules\Widget\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Cache;
use Modules\Tag\Entities\Tag;
use Modules\Widget\Entities\Widget;
use Modules\Widget\Enums\WidgetLocation;
use Modules\Widget\Enums\WidgetContentType;
use Modules\Language\Entities\Language;
use Validator;
use Modules\Ads\Entities\Ad;
use Modules\Post\Entities\Poll;

class WidgetController extends Controller
{

    public function widgets()
    {
        $widgets    = Widget::orderBy('id', 'desc')->paginate(15);

        return view('widget::widgets', compact('widgets'));
    }


    public function create()
    {
        $activeLang     = Language::where('status', 'active')->orderBy('name', 'ASC')->get();
        $ads            = Ad::orderBy('id', 'desc')->get();
        $polls            = Poll::orderBy('id', 'desc')->get();

        $tags           = Tag::orderby('id')->get();

        return view('widget::create', compact('activeLang', 'ads','tags', 'polls'));
    }

    public function store(Request $request)
    {
        if (strtolower(\Config::get('app.demo_mode')) == 'yes'):
            return redirect()->back()->with('error', __('You are not allowed to add/modify in demo mode.'));
        endif;
        Validator::make($request->all(), [
            'title'     => 'required',
        ])->validate();

        $widget             = new Widget();
        $widget->title      = $request->title;
        $widget->language   = $request->language;
        $widget->location   = $request->location;

        if ($request->location == WidgetLocation::RIGHT_SIDEBAR) :
            $widget->content_type   = $request->content_type;
        elseif ($request->location  == WidgetLocation::HEADER) :
            $widget->content_type   = $request->content_type_header;
        else:
            $widget->content_type   = $request->content_type_footer;
        endif;

        if ($widget->content_type   == WidgetContentType::AD) :
            $widget->ad_id          = $request->ad;
        endif;

        if ($widget->content_type   == WidgetContentType::VOTING_POLL) :
            $widget->poll_id          = $request->poll;
        endif;

        if ($widget->content_type   == WidgetContentType::TAGS) :
            Validator::make($request->all(), [
                'tags'          => 'required',
            ])->validate();
            $widget->content    = $request->tags;
        else:
            $widget->content        = $request->content;
        endif;

        $widget->order          = $request->order;
        $widget->status         = $request->status;

        $widget->save();
        Cache::Flush();

        return redirect()->back()->with('success', __('successfully_added'));
    }

    public function edit($id)
    {
        $widget         = Widget::findOrFail($id);

        $activeLang     = Language::where('status', 'active')->orderBy('name', 'ASC')->get();
        $ads            = Ad::orderBy('id', 'desc')->get();
        $polls            = Poll::orderBy('id', 'desc')->get();

        $tags           = Tag::orderBy('id')->get();

        return view('widget::edit', compact('widget', 'activeLang', 'ads','tags', 'polls'));
    }

    public function update(Request $request, $id)
    {
        if (strtolower(\Config::get('app.demo_mode')) == 'yes'):
            return redirect()->back()->with('error', __('You are not allowed to add/modify in demo mode.'));
        endif;
        Validator::make($request->all(), [
            'title'     => 'required',
        ])->validate();

        $widget             = Widget::findOrFail($id);
        $widget->title      = $request->title;
        $widget->language   = $request->language;
        $widget->location   = $request->location;

        if ($request->location == WidgetLocation::RIGHT_SIDEBAR) :
            $widget->content_type   = $request->content_type;
        elseif ($request->location  == WidgetLocation::HEADER) :
            $widget->content_type   = $request->content_type_header;
        else :
            $widget->content_type   = $request->content_type_footer;
        endif;

        if ($widget->content_type   == WidgetContentType::AD) :
            $widget->ad_id          = $request->ad;
        else :
            $widget->ad_id          = NULL;
        endif;

        if ($widget->content_type   == WidgetContentType::VOTING_POLL) :
            $widget->poll_id          = $request->poll;
        else :
            $widget->poll_id          = NULL;
        endif;

        if ($widget->content_type   == WidgetContentType::TAGS) :
            Validator::make($request->all(), [
                'tags'          => 'required',
            ])->validate();
            $widget->content    = $request->tags;
        else:
            $widget->content        = $request->content;
        endif;

        $widget->order              = $request->order;
        $widget->status             = $request->status;

        $widget->save();

        Cache::Flush();

        return redirect()->route('widgets')->with('success', __('successfully_added'));
    }

    public function destroy($id)
    {
        //
    }
}
