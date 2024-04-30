<?php

namespace Modules\Post\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use Modules\Language\Entities\Language;
use Modules\Gallery\Entities\Image as galleryImage;
use Modules\Ads\Entities\Ad;

class AddContentController extends Controller
{
    public function addContent(Request $request)
    {
        $page          = $request->value;
        $content_count = $request->content_count;
        $ads = [];
        if($page == 'ads'){
            $ads       = Ad::orderBy('id', 'desc')->get();
        }

        return view("post::contents.$page", compact('content_count', 'ads'));
    }

    public function btnImageModalContent($content_count)
    {
        
        $content_count = $content_count;
        $images         = galleryImage::orderBy('id','DESC')->paginate(24);
        return view("gallery::image-gallery-content", compact('content_count', 'images'));
    }
}
