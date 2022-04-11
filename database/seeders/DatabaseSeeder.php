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
use App\Models\Device;
use App\Models\DiagnosticReport;
use App\Models\DocumentReference;
use App\Models\Encounter;
use App\Models\Goal;
use App\Models\Immunization;
use App\Models\MedicationRequest;
use App\Models\Organization;
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
            /*
            'patient'=>[
                'reference'=>'Patient/'.$patient->id,
                'type'=>'Patient'
            ],
            */
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
                // Transmitter of the information
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
                        "reference" => "Organization/1"
                    ]
                ],
                // Author of the information
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
                        "reference" => "Organization/1"
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
                'reference'=>'Patient/'.strval($patient->id),
                'type' => 'Patient',
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
                'reference'=>'Patient/'.strval($patient->id),
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
                    'reference'=>'Patient/'.strval($patient->id),
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
                'reference'=>'Patient/'.strval($patient->id),
                'type'=>'Patient'
            ],
        ]);

        // Provenance of the created Condition
        $this->provenance('Condition/'.$condition->first()->id, 'Condition', $patient);
    }

    private function device($patient)
    {

        $device= Device::factory(1)->create([
            'patient' => [
                'reference'=>'Patient/'.strval($patient->id),
                'type'=>'Patient'
            ],
        ]);

        // Provenance of the created Condition
        $this->provenance('Device/'.$device->first()->id, 'Device', $patient);
    }

    private function diagnosticreport($patient)
    {

        // DiagnosticReport for Labs
        $diagnosticReport= DiagnosticReport::factory(1)->create([
            'subject' => [
                'reference'=>'Patient/'.strval($patient->id),
                'type'=>'Patient'
            ],
            'encounter' => [
                'reference'=>"Encounter/".strval($patient->id),
                'type'=>'Encounter'
            ],
            "performer" => [
                "reference"=>"Organization/".strval($patient->id),
                'type'=>'Organization'
            ],
            "presentedForm" => [
                "url"=>"http://www.demoreport.com/demoreport",
            ],
            'category'=> [
                "coding" => [
                    [
                        "system"=>"http://terminology.hl7.org/CodeSystem/v2-0074",
                        "code"=>"LAB",
                        "display" => "Laboratory"
                    ]
                ],
            ]
        ]);

        // Provenance of the created DiagnosticReport
        $this->provenance('DiagnosticReport/'.$diagnosticReport->first()->id, 'DiagnosticReport', $patient);

        // DiagnosticReport for Reports and Notes
        $diagnosticReport= DiagnosticReport::factory(1)->create([
            'subject' => [
                'reference'=>'Patient/'.strval($patient->id),
                'type'=>'Patient'
            ],
            'encounter' => [
                'reference'=>"Encounter/".strval($patient->id),
                'type'=>'Encounter'
            ],
            "performer" => [
                "reference"=>"Organization/".strval($patient->id),
                'type'=>'Organization'
            ],
            "presentedForm" => [
                "url"=>"http://www.demoreport.com/demoreport",
            ],
            'category'=> [
                "coding" => [
                    [
                        "system"=>"http://loinc.org",
                        "code"=>"LP29684-5",
                        "display"=>"Radiology"
                    ]
                ]
            ],
        ]);

        // Provenance of the created DiagnosticReport
        $this->provenance('DiagnosticReport/'.$diagnosticReport->first()->id, 'DiagnosticReport', $patient);


    }

    private function documentreference($patient)
    {

        $document= DocumentReference::factory(1)->create([
            'subject' => [
                'reference'=>'Patient/'.strval($patient->id),
                'type'=>'Patient'
            ],
            'author'=> [
                'reference'=>'Practitioner/'.$patient->id
            ]
        ])->first();

        $document->update(['identifier'=> [
            [
                "use"=>"usual",
                "system" => "urn:ietf:rfc:3986",
                "value" => $document->id
            ]
        ]]);
        // Provenance of the created Condition
        $this->provenance('DocumentReference/'.$document->first()->id, 'DocumentReference', $patient);
    }

    private function goal($patient)
    {
        $goal= Goal::factory(1)->create([
            'subject' => [
                'reference'=>'Patient/'.strval($patient->id),
                'type'=>'Patient'
            ],
        ]);

        // Provenance of the created Condition
        $this->provenance('Goal/'.$goal->first()->id, 'Goal', $patient);

    }

    private function immunization($patient)
    {
        $immunization= Immunization::factory(1)->create([
            'patient' => [
                'reference'=>'Patient/'.strval($patient->id),
                'type'=>'Patient'
            ],

        ]);

        // Provenance of the created Condition
        $this->provenance('Immunization/'.$immunization->first()->id, 'Immunization', $patient);

    }


    private function organization()
    {

        $organization= Organization::factory(1)->create()->first();

        $organization->update(['identifier'=> [
            [
                "system" => "urn:uuid:53fefa32-fcbb-4ff8-8a92-55ee120877b7",
                "value" => strval($organization->id)
            ],
        ]]);
    }

    private function organizationPayer1()
    {

        $organization= Organization::factory(1)->create()->first();

        $organization->update(['identifier'=> [
            [
                "system" => "urn:ietf:rfc:3986",
                "value" => "Payer1"
            ],
        ]]);
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
    }

    public function encounter($patient)
    {
        $encounter= Encounter::factory(1)->create([
            'subject' => [
                'reference'=>'Patient/'.strval($patient->id),
                'type'=>'Patient'
            ],
        ])->first();

        $encounter->update(['identifier'=> [
            [
                "use"=>"usual",
                "system" => "http://www.amc.nl/zorgportal/identifiers/visits",
                "value" => $encounter->id
            ]
        ]]);


        // Provenance of the created Condition
        $this->provenance('Encounter/'.$encounter->id, 'Encounter', $patient);

    }

    public function medicationrequest($patient)
    {
        $medicationrequest= MedicationRequest::factory(1)->create([
            'subject' => [
                'reference'=>'Patient/'.strval($patient->id),
                'type'=>'Patient'
            ],
            'intent'=>'proposal'
        ]);

        // Provenance of the created MedicationRequest
        $this->provenance('MedicationRequest/'.$medicationrequest->first()->id, 'MedicationRequest', $patient);

        $medicationrequest= MedicationRequest::factory(1)->create([
            'subject' => [
                'reference'=>'Patient/'.strval($patient->id),
                'type'=>'Patient'
            ],
            'intent'=>'plan'
        ]);

        // Provenance of the created MedicationRequest
        $this->provenance('MedicationRequest/'.$medicationrequest->first()->id, 'MedicationRequest', $patient);
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
            $this->device($patient);
            $this->diagnosticreport($patient);
            $this->documentreference($patient);
            $this->goal($patient);
            $this->immunization($patient);
            $this->encounter($patient);
            $this->medicationrequest($patient);
            $this->organization();
            $this->practitioner();
        });
        $this->organizationPayer1();

    }
}
