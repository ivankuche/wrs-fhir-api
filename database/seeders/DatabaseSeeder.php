<?php

namespace Database\Seeders;

use App\Models\AllergyIntolerance;
use App\Models\CarePlan;
use App\Models\CareTeam;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Patient;
use App\Models\Provenance;
use App\Models\Condition;

class DatabaseSeeder extends Seeder
{
    private function provenance($reference,$referenceType,$patient)
    {
        // Provenance of the created allergy intolerance
        $provenance= Provenance::factory(1)->create([
            'target'=>[
                'reference'=>$reference,
                'type'=>$referenceType
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
    }

    private function allergyIntolerance($patient)
    {
        // Allergy Intolerance per patient
        $allergyIntolerance= AllergyIntolerance::factory(1)->create([
            'patient' => [
                'reference'=>strval($patient->id),
            ]
        ]);

        // Provenance of the created allergy intolerance
        $this->provenance('AllergyIntolerance/'.$allergyIntolerance->first()->id, 'AllergyIntolerance', $patient);
    }

    private function carePlan($patient)
    {
        // CarePlan per patient
        $careplan= CarePlan::factory(1)->create([
            'subject' => [
                'reference'=>strval($patient->id),
                'type'=>'Patient'
            ]
        ]);

        // Provenance of the created CarePlan
        $this->provenance('CarePlan/'.$careplan->first()->id, 'CarePlan', $patient);
    }

    private function careTeam($patient)
    {

        $statuses= ['active','inactive', 'entered-in-error', 'proposed', 'suspended'];

        foreach ($statuses as $status)
        {

            $careteam= CareTeam::factory(1)->create([
                'subject' => [
                    'reference'=>strval($patient->id),
                    'type'=>'Patient'
                ],
                'status' => $status,
            ]);

            // Provenance of the created CarePlan
            $this->provenance('CareTeam/'.$careteam->first()->id, 'CareTeam', $patient);
        }
    }

    private function condition($patient)
    {

        $condition= Condition::factory(1)->create([
            'subject' => [
                'reference'=>strval($patient->id),
                'type'=>'Patient'
            ],
        ]);

        // Provenance of the created Condition
        $this->provenance('Condition/'.$condition->first()->id, 'Condition', $patient);
    }

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
            $this->provenance('Patient/'.$patient->id,'Patient',$patient);

            $this->allergyIntolerance($patient);
            $this->carePlan($patient);
            $this->careTeam($patient);
            $this->condition($patient);
        });

    }
}
