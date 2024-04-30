<?php

namespace Modules\Post\Entities;

use Illuminate\Database\Eloquent\Model;

class QuizAnswer extends Model
{
    protected $fillable = [];

    public function quizQuestion(){
        return $this->belongsTo('Module\Post\Entities\QuizQuestion');
    }

    public function image(){
        return $this->belongsTo('Modules\Gallery\Entities\Image');
    }

}
