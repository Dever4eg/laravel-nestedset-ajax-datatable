<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Employee extends Model
{

    public function subordinates()
    {
        return $this->hasMany('App\Employee', 'chief_id')->with('subordinates');
    }

    public function chief()
    {
        return $this->belongsTo('App\Employee', 'chief_id');
    }


    public static function HideProp($collection, $properties)
    {
        $arr = [];
        foreach ($collection as $item) {
            $arr[] = $item->makeHidden($properties);
            if(!empty($item->children))
                $item->children = self::HideProp($item->children, $properties);
        }
        return $arr;
    }

}
