<?php

namespace Database\Seeders;

use App\Models\AllergyIntolerance;
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

            // Create on allergy per patient, and also store the provenance
            $allergyIntolerance= AllergyIntolerance::factory(1)->create([
                'patient' => [
                    'reference'=>$patient->id,
                ]
            ]);
            // 1 provenance per each patient created
            $provenance= Provenance::factory(1)->create([
                'target'=>[
                    'reference'=>'AllergyIntolerance/'.$allergyIntolerance->get('id'),
                    'type'=>'AllergyIntolerance'
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
