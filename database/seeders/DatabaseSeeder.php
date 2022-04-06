<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Patient;
use App\Models\Provenance;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $patients= Patient::factory(20)->create();

        $patients->each(function($patient) {
            $patientObjSource= Patient::find($patient->id);
            $patientObjSource->update(['identifier'=> [
                'use'=>'usual',
                'value'=>$patient->id,
                'system'=> "http://hospital.smarthealthit.org"
            ]]);

            // 1 provenance per each patient created
            $provenance= Provenance::factory(1)->create([
                'target'=>[
                    'reference'=>'Patient/'.$patient->id,
                    'type'=>'Patient'
                ],
                'patient'=>[
                    'reference'=>'Patient/'.$patient->id,
                    'type'=>'Patient'
                ],
                'agent'=>[
                    'who'=> [
                        'reference'=>'Patient/'.$patient->id,
                        'type'=>'Patient'
                    ]
                ],
            ]);




        });

    }
}
