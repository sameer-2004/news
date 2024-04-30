<?php

namespace Modules\Post\Entities;

use Illuminate\Database\Eloquent\Model;

class QuizResult extends Model
{
    protected $fillable = [];

    public function post(){
        return $this->belongsTo('Modules\Post\Entities\Post');
    }

    public function image(){
        return $this->belongsTo('Modules\Gallery\Entities\Image');
    }

    public function quizAnswers(){
        return $this->hasMany('Modules\Post\Entities\QuizAnswer');
    }
}
