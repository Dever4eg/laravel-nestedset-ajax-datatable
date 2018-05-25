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
        $root = factory(App\Employee::class)->create(['position' => 'General director']);

        $users = factory(App\Employee::class, 15)->create()->each(function ($user) use ($root) {
            $user->chief_id = $root->id;
            $user->save();
        });

        $users = factory(App\Employee::class, 200)->create()->each(function ($user) use ($users) {
            $user->chief_id = $users[rand(0, count($users)-1)]->id;
            $user->save();
        });

        $users = factory(App\Employee::class, 4684)->create()->each(function ($user) use ($users) {
            $user->chief_id = $users[rand(0, count($users)-1)]->id;
            $user->save();
        });

        for($i=0; $i<3;$i++) {
            factory(App\Employee::class, 15000)->create()->each(function ($user) use ($users) {
                $user->chief_id = $users[rand(0, count($users)-1)]->id;
                $user->save();
            });
        }
    }
}
