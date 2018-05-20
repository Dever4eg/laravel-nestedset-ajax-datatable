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

    public function destroy(Request $request) {
        $id = $request->validate(['id' => 'required|integer|min:1'])['id'];
        return Employee::destroy($id) ?
            response("Deleted", 200) :
            response("Error 404", 404);
    }

    public function store(Request $request) {
        $v = $request->validate([
            'id'        => 'nullable|integer|min:0',
            'fullname'  => 'required|string',
            'position'  => 'required|string',
            'salary'    => 'required|integer|min:0',
            'date'      => 'required|date',
            'chief_id'  => 'nullable|integer|min:0'
        ]);


        $employee = Employee::find($v['id']);
        $employee = !empty($employee) ? $employee : new Employee();
        if( $employee->fill($v)->save() )
            return response()->json('Employee was updated', 200);
        return response()->json("Error. Employee was not updated");
    }

    public function show(Request $request)
    {
        $id = $request->validate(['id' => 'required|integer|min:1'])['id'];
        return Employee::find($id);
    }

    public function GetData(Request $request)
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

        $query = Employee::select('id', 'fullname', 'position', 'date', 'salary', 'chief_id')
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
        $employees = Employee::where('chief_id', $request['parentId'])
            ->with('subordinates')
            ->get();
        $employees = Employee::LazyLoadPrepare($employees);

        return response()->json($employees);
    }
}
