<?php

namespace App\Http\Controllers;

use App\Employee;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

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
        $elployee = Employee::findOrFail($id);

        DB::transaction(function () use ($id, $elployee) {
            Employee::where('chief_id', $id)
                ->update(['chief_id' => $elployee->chief_id]);

            $elployee->delete();
        });

        return response("Employee deleted", 200);
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
        $v = $request->validate(['parentId' => 'nullable|integer|min:0']);
        $chief_id = isset($v['parentId']) ? $v['parentId'] : null;

        $employees = Employee::where('chief_id', $chief_id)
            ->with('subordinates')
            ->get();
        $employees = Employee::LazyLoadPrepare($employees);

        return response()->json($employees);
    }
}
