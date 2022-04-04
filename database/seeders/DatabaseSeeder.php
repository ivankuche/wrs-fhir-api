<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Patient;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $patients= Patient::factory(100)->create();

        $patients->each(function($patient) {
            $patientObjSource= Patient::find($patient->id);
            $patientObjSource->update(['identifier'=> json_encode([
                'use'=>'usual',
                'value'=>$patient->id
            ])]);
        });

    }
}
