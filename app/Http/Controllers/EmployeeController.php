<?php

namespace App\Http\Controllers;

use App\Employee;
use Illuminate\Http\Request;

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


    public function GetData(Request $request)
    {
        $columns = ['fullname', 'position', 'date', 'salary'];

        $length = $request->input('length');
        $column = $request->input('column');
        $dir = $request->input('dir');
        $searchValue = $request->input('search');
        $query = Employee::select('fullname', 'position', 'date', 'salary')->orderBy($columns[$column], $dir);
        if ($searchValue) {
            $query->where(function($query) use ($searchValue) {
                $query->where('fullname', 'like', '%' . $searchValue . '%')
                    ->orWhere('position', 'like', '%' . $searchValue . '%');
            });
        }
        $employees = $query->paginate($length);
        return ['data' => $employees, 'draw' => $request->input('draw')];
    }

    public function LazyLoadTree(Request $request)
    {
        $employees = Employee::where('chief_id', $request['parentId'])
            ->with('subordinates')
            ->get();
        $employees = Employee::LazyLoadPrepare($employees);

        return response()->json($employees);
    }
}
