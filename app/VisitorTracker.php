<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Config;

class VisitorTracker extends Model
{
    public function post(){
        //   return $this->hasOne(Media::class ,'id', 'avatar_id');
        return $this->belongsTo('Modules\Post\Entities\Post', 'slug', 'slug');
    }
    public function posts(){
        $language = Config::get('app.locale') ?? settingHelper('default_language');
        //   return $this->hasOne(Media::class ,'id', 'avatar_id');
        return $this->belongsTo('Modules\Post\Entities\Post', 'slug', 'slug')->where('language',$language);
    }
}
