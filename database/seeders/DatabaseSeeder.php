<?php

namespace Database\Seeders;

use App\Models\AllergyIntolerance;
use App\Models\CarePlan;
use App\Models\CareTeam;
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
            // Patient creation
            $patientObjSource= Patient::find($patient->id);
            $patientObjSource->update(['identifier'=> [
                'use'=>'usual',
                'value'=>$patient->id,
                'system'=> "http://hospital.smarthealthit.org"
            ]]);

            // Provenance of the created patient
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



            // Allergy Intolerance per patient
            $allergyIntolerance= AllergyIntolerance::factory(1)->create([
                'patient' => [
                    'reference'=>strval($patient->id),
                ]
            ]);

            // Provenance of the created allergy intolerance
            $provenance= Provenance::factory(1)->create([
                'target'=>[
                    'reference'=>'AllergyIntolerance/'.$allergyIntolerance->first()->id,
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

            // CarePlan per patient
            $careplan= CarePlan::factory(1)->create([
                'subject' => [
                    'reference'=>strval($patient->id),
                    'type'=>'Patient'
                ]
            ]);

            // Provenance of the created CarePlan
            $provenance= Provenance::factory(1)->create([
                'target'=>[
                    'reference'=>'CarePlan/'.$careplan->first()->id,
                    'type'=>'CarePlan'
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

            // CareTeam per patient
            $careteam= CareTeam::factory(1)->create([
                'subject' => [
                    'reference'=>strval($patient->id),
                    'type'=>'Patient'
                ]
            ]);

            // Provenance of the created CareTeam
            $provenance= Provenance::factory(1)->create([
                'target'=>[
                    'reference'=>'CareTeam/'.$careteam->first()->id,
                    'type'=>'CareTeam'
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
