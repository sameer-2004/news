<?php

namespace Modules\Gallery\Entities;

use Illuminate\Database\Eloquent\Model;

class Album extends Model
{
    protected $fillable = [];

//    public function imageCategories()
//    {
//        return $this->hasMany('Modules\Gallery\Entities\ImageCategory');
//    }

    public function galleryImages()
    {
        return $this->hasMany('Modules\Gallery\Entities\GalleryImage');
    }
}
