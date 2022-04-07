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
use App\Models\Practitioner;

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
                [
                    // On behalf of which organization
                    'onBehalfOf'=> [
                        'reference'=>'Organization/'.$patient->id,
                        'type'=>'Organization'
                    ],
                    'type'=> [
                        "coding"=> [
                            [
                            "system"=> "http://terminology.hl7.org/CodeSystem/v3-ParticipationType",
                            "code"=> "AUT"
                            ]
                        ]
                    ],
                    'who'=> [
                        'reference'=>'Practitioner/'.$patient->id,
                        'type'=>'Practitioner'
                    ],
                ],
                [
                    "type"=>[
                        "coding" => [
                            [
                                "system"=>"http://hl7.org/fhir/us/core/CodeSystem/us-core-provenance-participant-type",
                                "code" =>"transmitter",
                                "display" => "Transmitter"
                            ]
                        ]
                    ],
                    "who" => [
                        "reference" => "Organization/Payer1"
                    ]
                ],
                [
                    "type"=>[
                        "coding" => [
                            [
                                "system"=>"http://terminology.hl7.org/CodeSystem/provenance-participant-type",
                                "code" =>"author",
                                "display" => "Author"
                            ]
                        ]
                    ],
                    "who" => [
                        "reference" => "Organization/Author1"
                    ]
                ]
                // Extensions
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

    private function practitioner()
    {

        $practitioner= Practitioner::factory(1)->create()->first();

        $practitioner->update(['identifier'=> [
            [
                "system" => "http://hl7.org/fhir/sid/us-npi",
                "value" => "1231".$practitioner->id
            ],
            [
                "use"=>"usual",
                "system" => "http://www.acme.org/practitioners",
                "value" => $practitioner->id
            ]
        ]]);
        /*/
        $practitioner->update(['identifier'=> [
            'use'=>'usual',
            'value'=>$practitioner->id,
            'system'=> "http://hospital.smarthealthit.org"
        ]]);
        */
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
            $this->practitioner();
        });

    }
}
