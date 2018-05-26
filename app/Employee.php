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

    public function isDescendant($id)
    {
        $e = self::with('chief')->find($id);
        if( empty($e) || empty($e->chief))
            return false;
        return $this->id == $e->chief->id || self::isDescendant($e->chief->id);
    }

    public static function LazyLoadPrepare($collection, $hideProperties = null)
    {
        $arr = [];

        foreach ($collection as $item) {
            $item->text = view('parts.employee')->with([
                'fullname'  => $item->fullname,
                'position'  => $item->position
            ])->render();

            $item->hasChildren = $item->subordinates->isNotEmpty();
            $item->children = [];

            $item->makeHidden(['position', 'fullname', 'subordinates',
                'salary', 'created_at', 'updated_at', 'date', 'chief_id'
            ]);

            $arr[] = $item;
        }

        return $arr;
    }

}
