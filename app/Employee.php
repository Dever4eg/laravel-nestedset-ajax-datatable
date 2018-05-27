<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Employee extends Model
{

    protected $fillable = ['fullname', 'position', 'salary', 'date'];

    public function subordinates()
    {
        return $this->hasMany('App\Employee', 'chief_id');
    }

    public function chief()
    {
        return $this->belongsTo('App\Employee', 'chief_id');
    }

    public function isDescendant($employee)
    {
        if( empty($employee->chief_id) || empty($employee->chief))
            return false;
        return $this->id == $employee->chief->id || self::isDescendant($employee->chief);
    }

    public static function LazyLoadPrepare($collection)
    {
        $arr = [];

        foreach ($collection as $item) {

            $item->hasChildren = $item->subordinates->isNotEmpty();
            $item->children = [];
            $item->collapsed = false;
            $item->makeHidden(['created_at', 'updated_at', 'subordinates']);

            $arr[] = $item;
        }

        return $arr;
    }

}
