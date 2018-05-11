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
        factory(App\Employee::class, 10)->create([
            'position' => "directior",
        ])->each(function ($employee) {
            factory(App\Employee::class, 5)->create([
                'position'  => "directior",
                'chief_id'  => $employee->id
            ])->each(function ($employee) {
                factory(App\Employee::class, 5)->create([
                    'position'  => "directior",
                    'chief_id'  => $employee->id
                ])->each(function ($employee) {
                    factory(App\Employee::class, 5)->create([
                        'position'  => "directior",
                        'chief_id'  => $employee->id
                    ]);
                });
            });
        });
    }
}
