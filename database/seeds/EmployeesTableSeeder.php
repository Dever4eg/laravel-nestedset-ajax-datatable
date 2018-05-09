<?php

use Illuminate\Database\Seeder;
use Carbon\Carbon;
use \App\Employee;

class EmployeesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $emp = new Employee();
        $emp->fullname  = 'Буров Владислав Иванович';
        $emp->position  = 'Директор';
        $emp->salary    = 2000;
        $emp->date      = Carbon::parse('2000-01-04');
        $emp->save();

        for($i =0;$i<10;$i++) {
            $e = new Employee();
            $e->fullname    = 'Буров Сергей Иванович';
            $e->position    = 'Директор';
            $e->salary      = 2000;
            $e->date        = Carbon::parse('2000-01-04');
            $e->chief_id    = $emp->id;
            $e->save();
        }
    }
}
