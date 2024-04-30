<?php

namespace Modules\Gallery\Entities;

use Illuminate\Database\Eloquent\Model;

class GalleryImage extends Model
{
    protected $fillable = [];

    public function album()
    {
        return $this->belongsTo('Modules\Gallery\Entities\Album');
    }

//    public function imageCategory()
//    {
//        return $this->belongsTo('Modules\Gallery\Entities\ImageCategory');
//    }

}
