<?php

namespace Modules\Post\Entities;

use Illuminate\Database\Eloquent\Model;

class RssFeed extends Model
{
    protected $fillable = [];

    public function category(){
       return $this->belongsTo('Modules\Post\Entities\Category');
    }
    public function subCategory(){
        return $this->belongsTo('Modules\Post\Entities\SubCategory');
    }
}
