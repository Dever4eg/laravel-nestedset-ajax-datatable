<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Employee extends Model
{

    public function children()
    {
        return $this->hasMany('App\Employee', 'chief_id')->with('children');
    }

    public function parent()
    {
        return $this->belongsTo('App\Employee', 'chief_id');
    }

    public static function GetTree()
    {
        return self::doesntHave('parent')->with('children')->get();
    }

}
