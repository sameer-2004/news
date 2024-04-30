<?php

namespace Modules\Gallery\Entities;

use Illuminate\Database\Eloquent\Model;

class Audio extends Model
{
    protected $fillable = [];

    public function posts(){
        return $this->belongsToMany('Modules\Post\Entities\Post')->withTimestamps();
    }
}
