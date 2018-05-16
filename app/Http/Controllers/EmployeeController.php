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


    public function GetData()
    {
        //
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
