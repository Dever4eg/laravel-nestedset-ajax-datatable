<?php

namespace App\Http\Controllers;

use App\Employee;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\Facades\Image;

class EmployeeController extends Controller
{

    public function Tree()
    {
        return view('tree');
    }

    public function DataView()
    {
        return view('dataview');
    }

    public function destroy(Request $request) {
        $id = $request->validate(['id' => 'required|integer|min:1'])['id'];
        $employee = Employee::findOrFail($id);

        $avatar = $employee->avatar;

        DB::transaction(function () use ($id, $employee) {
            Employee::where('chief_id', $id)
                ->update(['chief_id' => $employee->chief_id]);

            $employee->delete();
        });

        if(!empty($avatar)) {
            Storage::delete('avatars/' . $avatar);
            Storage::delete('avatars/thumbnails/' . $avatar);
        }

        return response("Employee deleted", 200);
    }

    public function store(Request $request) {

        $v = $request->validate([
            'id'        => 'nullable|integer|min:0',
            'fullname'  => 'required|string',
            'position'  => 'required|string',
            'salary'    => 'required|integer|min:0',
            'date'      => 'required|date',
            'chief_id'  => 'nullable|integer|min:0|different:id',
            'avatar'    => 'nullable|image|max:2048',
        ]);


        $employee = Employee::find($v['id']);
        $employee = !empty($employee) ? $employee : new Employee();

        $employee->fill($v);

        if(!empty($v['chief_id'] && $employee->chief_id != $v['chief_id']) ) {
            $new_chief = Employee::with('chief')->findOrFail($v['chief_id']);

            // Если новый начальник является потомком даного елемента,
            // то все дочерние елементы поднимаем на уровень выше и изменяем начальника
            if($employee->isDescendant($new_chief)) {

                DB::transaction(function () use ($v, $employee, $new_chief) {
                    Employee::where('chief_id', $employee->id)
                        ->update(['chief_id' => $employee->chief_id]);

                    $employee->chief()->associate($new_chief);
                });
            } else {
                $employee->chief()->associate($new_chief);
            }
        }



        if($request->hasFile('avatar')) {
            $image = $request->file('avatar');
            $filename = time() . '.' . $image->getClientOriginalExtension();

            $file = Image::make($image)->resize(600, null, function ($constraint) {
                $constraint->aspectRatio();
                $constraint->upsize();
            });
            Storage::put('avatars/'. $filename, $file->stream()->__toString());

            $file = Image::make($image)->resize(100, null, function ($constraint) {
                $constraint->aspectRatio();
                $constraint->upsize();
            });

            Storage::put('avatars/thumbnails/'.$filename, $file->stream()->__toString());

            if(!empty($employee->avatar)) {
                Storage::delete('avatars/' . $employee->avatar);
                Storage::delete('avatars/thumbnails/' . $employee->avatar);
            }
            $employee->avatar = $filename;
        }

        if( $employee->save() )
            return response()->json([
                'message' => 'Employee was updated',
                'avatar' => isset($filename) ? $filename : $employee->avatar,
            ], 200);
        return response()->json("Error. Employee was not updated");
    }


    public function getData(Request $request)
    {
        $validated = $request->validate([
            'sortKey'   => 'required|in:fullname,position,date,salary',
            'sortDir'   => 'required|in:asc,desc',
            'pageSize'  => 'required|integer|min:1',
            'search'    => 'max:255',
        ]);

        $pageSize   = $validated['pageSize'];
        $sortKey    = $validated['sortKey'];
        $sortDir    = $validated['sortDir'];
        $search     = $validated['search'];

        $query = Employee::select('id', 'fullname', 'position', 'date', 'salary', 'chief_id', 'avatar')
            ->with('chief')
            ->orderBy($sortKey, $sortDir);

        if ($search) {
            $query->where(function($query) use ($search) {
                $query->where('fullname', 'like', '%' . $search . '%')
                    ->orWhere('position', 'like', '%' . $search . '%')
                    ->orWhere('date', 'like', '%' . $search . '%')
                    ->orWhere('salary', 'like', '%' . $search . '%');
            });
        }

        return $query->paginate($pageSize);
    }

    public function LazyLoadTree(Request $request)
    {
        $v = $request->validate(['chief_id' => 'nullable|integer|min:0']);
        $chief_id = isset($v['chief_id']) ? $v['chief_id'] : null;

        $employees = Employee::where('chief_id', $chief_id)
            ->with('subordinates')
            ->get();
        Employee::LazyLoadPrepare($employees);

        return $employees;
    }
}
