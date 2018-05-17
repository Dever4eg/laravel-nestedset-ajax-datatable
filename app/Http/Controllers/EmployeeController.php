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
        $PageSize   = $request->input('PageSize');
        $sortKey    = $request->input('sortKey');
        $sortDir    = $request->input('sortDir');
        $search     = $request->input('search');

        $query = Employee::select('fullname', 'position', 'date', 'salary')->orderBy($sortKey, $sortDir);

        if ($search) {
            $query->where(function($query) use ($search) {
                $query->where('fullname', 'like', '%' . $search . '%')
                    ->orWhere('position', 'like', '%' . $search . '%')
                    ->orWhere('date', 'like', '%' . $search . '%')
                    ->orWhere('salary', 'like', '%' . $search . '%');
            });
        }

        return $query->paginate($PageSize);
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
