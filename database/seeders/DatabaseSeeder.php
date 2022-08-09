<?php

namespace Database\Seeders;

use App\Models\AllergyIntolerance;
use App\Models\CarePlan;
use App\Models\CareTeam;
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
use App\Models\Medication;
use App\Models\MedicationRequest;
use App\Models\Observation;
use App\Models\Organization;
use App\Models\Practitioner;
use App\Models\Procedure;
use App\Models\Location;
use App\Models\Group;

class DatabaseSeeder extends Seeder
{
    var $practitionerDefault= 3346591;
    var $organizationDefault= 983266; //1

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
                        'reference'=>'Organization/'.$this->organizationDefault,
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
                        'reference'=>'Practitioner/'.$this->practitionerDefault,//.$patient->id,
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
                        "reference" => "Organization/".$this->organizationDefault,
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
                        "reference" => "Organization/".$this->organizationDefault,
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
                'reference'=>"Encounter/1",//.strval($patient->id),
                'type'=>'Encounter'
            ],
            "performer" => [
                "reference"=>"Organization/".$this->organizationDefault,//.strval($patient->id),
                'type'=>'Organization'
            ],
            "result" => [
                "reference"=>"Observation/1",//.strval($patient->id),
                'type'=>'Observation'
            ],
            'category'=> [
                "coding" => [
                    [
                        "system"=>"http://terminology.hl7.org/CodeSystem/v2-0074",
                        "code"=>"LAB",
                        "display" => "Laboratory"
                    ]
                ],
            ],
            "presentedForm" => [
                "url"=>"http://www.demoreport.com/demoreport/".$patient->id.".txt",
            ],

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
                'reference'=>"Encounter/1",//.strval($patient->id),
                'type'=>'Encounter'
            ],
            "performer" => [
                "reference"=>"Organization/".$this->organizationDefault,//.strval($patient->id),
                'type'=>'Organization'
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
            "presentedForm" => [
                "url"=>"http://www.demoreport.com/demoreport/".$patient->id.".txt",
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
                'reference'=>'Practitioner/'.$this->practitionerDefault,//.$patient->id
            ],
            'content' => [
                'attachment' => [
                    'url' => "http://www.demoreport.com/demoreport/".$patient->id.".txt",
                    'contentType' => 'text/plain',
                ],
                "format" => [
                    "system"=>"urn:oid:1.3.6.1.4.1.19376.1.2.3",
                    "code"=>"urn:ihe:pcc:handp:2008"
                ],
            ],


        ])->first();

        $document->update(['identifier'=> [
            [
                "use"=>"usual",
                //"system" => "urn:ietf:rfc:3986",
                "value" => strval($document->id)
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

        $organization= Organization::factory(1)->create(['id'=>983266])->first();

        $organization->update(['identifier'=> [
            [
                "system" => "urn:uuid:53fefa32-fcbb-4ff8-8a92-55ee120877b7",
                "value" => strval($organization->id)
            ],
            [
                "system" => "http://hl7.org/fhir/sid/us-npi",
                "value" => "1231".$organization->id
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

        $practitioners= Practitioner::factory(10)->create();

        $practitioners->each(function($practitioner) {
            // Practitioner creation
            $practitionerObjSource= Practitioner::find($practitioner->id);
            $practitionerObjSource->update(['identifier'=> [
                [
                    "system" => "http://hl7.org/fhir/sid/us-npi",
                    "value" => $practitioner->id, //"1231".$practitioner->id
                ],
                [
                    "use"=>"usual",
                    "system" => "http://www.acme.org/practitioners",
                    "value" => $practitioner->id
                ]
            ]]);
        });
    }

    private function encounter($patient)
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
                "value" => strval($encounter->id)
            ]
        ]]);


        // Provenance of the created Condition
        $this->provenance('Encounter/'.$encounter->id, 'Encounter', $patient);

    }

    private function medication()
    {
        Medication::factory(1)->create();
    }

    private function medicationrequest($patient)
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

    private function observation($patient)
    {

        // Smoking Status
        $observation= Observation::factory(1)->create([
            'subject' => [
                'reference'=>'Patient/'.strval($patient->id),
                'type'=>'Patient'
            ],
            "code"=> [
                "coding"=> [
                    [
                        "system"=>"http://loinc.org",
                        "code"=>"72166-2",
                        "display"=>"Tobacco smoking status"
                    ]
                ]
            ],
            "valueCodeableConcept" => [
               "coding" => [
                    [
                        "system" =>"http://snomed.info/sct",
                        "code" => "428041000124106"
                    ]
                ],
                "text" =>"Current some day smoker"
            ]
        ]);

        // Provenance of the created Observation
        $this->provenance('Observation/'.$observation->first()->id, 'Observation', $patient);


        // Pediatric Weight for Height
        $observation= Observation::factory(1)->create([
            'subject' => [
                'reference'=>'Patient/'.strval($patient->id),
                'type'=>'Patient'
            ],
            "category" =>[
                [
                    "coding" => [
                        [
                            "system"=>"http://terminology.hl7.org/CodeSystem/observation-category",
                            "code"=>"vital-signs",
                            "display"=>"Vital Signs"
                        ]
                    ],
                    "text" => "Vital Signs"
                ]
            ],
            "code"  => [
                "coding" => [
                    [
                        "system"=>"http://loinc.org",
                        "code"=>"77606-2",
                        "display"=>"Weight-for-length Per age and sex"
                    ]
                ],
                "text" => "BMI"
            ],
            "valueQuantity" => [
                "value" =>65,
                "unit" => "%",
                "system" => "http://unitsofmeasure.org",
                "code" => "%"
            ]
        ]);

        // Provenance of the created Observation
        $this->provenance('Observation/'.$observation->first()->id, 'Observation', $patient);

        // Laboratory Result
        $observation= Observation::factory(1)->create([
            'subject' => [
                'reference'=>'Patient/'.strval($patient->id),
                'type'=>'Patient'
            ],
            "category" =>[
                [
                    "coding" => [
                        [
                            "system"=>"http://terminology.hl7.org/CodeSystem/observation-category",
                            "code"=>"laboratory",
                            "display"=>"Laboratory"
                        ]
                    ],
                    "text" => "Laboratory"
                ]
            ],
            "code"  => [
                "coding" => [
                    [
                        "system"=>"http://loinc.org",
                        "code"=>"5811-5",
                        "display"=>"Specific gravity of Urine by Test strip"
                    ]
                ],
                "text" => "SPEC GRAV"
            ],
            "valueQuantity" => [
                "value" =>1.017,
                "system" => "http://unitsofmeasure.org",
                "code" => "{urine specific gravity}"
            ]
        ]);

        // Provenance of the created Observation
        $this->provenance('Observation/'.$observation->first()->id, 'Observation', $patient);

        // Pediatric BMI for Age
        $observation= Observation::factory(1)->create([
            'subject' => [
                'reference'=>'Patient/'.strval($patient->id),
                'type'=>'Patient'
            ],
            "category" =>[
                [
                    "coding" => [
                        [
                            "system"=>"http://terminology.hl7.org/CodeSystem/observation-category",
                            "code"=>"vital-signs",
                            "display"=>"Vital Signs"
                        ]
                    ],
                    "text" => "Vital Signsn"
                ]
            ],
            "code"  => [
                "coding" => [
                    [
                        "system"=>"http://loinc.org",
                        "code"=>"59576-9",
                        "display"=>"Body mass index (BMI) [Percentile] Per age and sex"
                    ]
                ],
                "text" => "BMI"
            ],
            "valueQuantity" => [
                "value" =>65,
                "unit"  => "%",
                "code" => "%",
                "system" => "http://unitsofmeasure.org",
            ]
        ]);

        // Provenance of the created Observation
        $this->provenance('Observation/'.$observation->first()->id, 'Observation', $patient);

        // Pulse oximetry
        $observation= Observation::factory(1)->create([
            'subject' => [
                'reference'=>'Patient/'.strval($patient->id),
                'type'=>'Patient'
            ],
            "category" =>[
                [
                    "coding" => [
                        [
                            "system"=>"http://terminology.hl7.org/CodeSystem/observation-category",
                            "code"=>"vital-signs",
                            "display"=>"Vital Signs"
                        ]
                    ],
                    "text" => "Vital Signsn"
                ]
            ],




            "component"=> [
                [
                    "code" => [
                        "coding"=> [
                            [
                                "system"=>"http://loinc.org",
                                "code"=>"3151-8",
                                "display"=>"Inhaled oxygen flow rate"
                            ],
                        ]
                    ],
                    "valueQuantity" => [
                        "value" =>5,
                        "unit"  => "L/min",
                        "system" => "http://unitsofmeasure.org",
                        "code" =>  "L/min",
                    ],
                ],
                [
                    "code" => [
                        "coding"=> [
                            [
                                "system"=>"http://loinc.org",
                                "code"=>"3150-0",
                                "display"=>"Inhaled oxygen concentration"
                            ],
                        ]
                    ],
                    "valueQuantity" => [
                        "value" =>92,
                        "unit"  => "%",
                        "system" => "http://unitsofmeasure.org",
                        "code" =>  "%",
                    ],
                ],
            ],
            "code"  => [
                "coding" => [
                    [
                        "system"=>"http://loinc.org",
                        "code"=>"2708-6",
                        "display"=>"Oxygen saturation in Arterial blood"
                    ],
                    [
                        "system" => "http://loinc.org",
                        "code" => "59408-5",
                        "display" => "Oxygen saturation in Arterial blood by Pulse oximetry"
                    ]
                ],
                "text" => "oxygen_saturation"
            ],
            "valueQuantity" => [
                "value" =>99,
                "unit"  => "%O2",
                "code" => "%",
                "system" => "http://unitsofmeasure.org",
            ]
        ]);

        // Provenance of the created Observation
        $this->provenance('Observation/'.$observation->first()->id, 'Observation', $patient);

        // Pediatric Head Occipital-frontal Circumference Percentile
        $observation= Observation::factory(1)->create([
            'subject' => [
                'reference'=>'Patient/'.strval($patient->id),
                'type'=>'Patient'
            ],
            "category" =>[
                [
                    "coding" => [
                        [
                            "system"=>"http://terminology.hl7.org/CodeSystem/observation-category",
                            "code"=>"vital-signs",
                            "display"=>"Vital Signs"
                        ]
                    ],
                    "text" => "Vital Signsn"
                ]
            ],
            "code"  => [
                "coding" => [
                    [
                        "system"=>"http://loinc.org",
                        "code"=>"8289-1",
                        "display"=>"Head Occipital-frontal circumference Percentile"
                    ]
                ],
                "text" => "Head Occipital-frontal circumference Percentile"
            ],
            "valueQuantity" => [
                "value" =>82,
                "unit"  => "%",
                "code" => "%",
                "system" => "http://unitsofmeasure.org",
            ]
        ]);

        // Provenance of the created Observation
        $this->provenance('Observation/'.$observation->first()->id, 'Observation', $patient);

        // Body Height
        $observation= Observation::factory(1)->create([
            'subject' => [
                'reference'=>'Patient/'.strval($patient->id),
                'type'=>'Patient'
            ],
            "category" =>[
                [
                    "coding" => [
                        [
                            "system"=>"http://terminology.hl7.org/CodeSystem/observation-category",
                            "code"=>"vital-signs",
                            "display"=>"Vital Signs"
                        ]
                    ],
                    "text" => "Vital Signsn"
                ]
            ],
            "code"  => [
                "coding" => [
                    [
                        "system"=>"http://loinc.org",
                        "code"=>"8302-2",
                        "display"=>"Body height"
                    ]
                ],
                "text" => "Body height"
            ],
            "valueQuantity" => [
                "value" =>172,
                "unit"  => "cm",
                "system" => "http://unitsofmeasure.org",
                "code" => "cm"
            ]
        ]);

        // Provenance of the created Observation
        $this->provenance('Observation/'.$observation->first()->id, 'Observation', $patient);

        // Body Temperature
        $observation= Observation::factory(1)->create([
            'subject' => [
                'reference'=>'Patient/'.strval($patient->id),
                'type'=>'Patient'
            ],
            "category" =>[
                [
                    "coding" => [
                        [
                            "system"=>"http://terminology.hl7.org/CodeSystem/observation-category",
                            "code"=>"vital-signs",
                            "display"=>"Vital Signs"
                        ]
                    ],
                    "text" => "Vital Signsn"
                ]
            ],
            "code"  => [
                "coding" => [
                    [
                        "system"=>"http://loinc.org",
                        "code"=>"8310-5",
                        "display"=>"Body temperature"
                    ]
                ],
                "text" => "Body temperature"
            ],
            "valueQuantity" => [
                "value" =>38.1,
                "unit"  => "Cel",
                "system" => "http://unitsofmeasure.org",
                "code" => "Cel"
            ]
        ]);

        // Provenance of the created Observation
        $this->provenance('Observation/'.$observation->first()->id, 'Observation', $patient);

        // Blood pressure
        $observation= Observation::factory(1)->create([
            'subject' => [
                'reference'=>'Patient/'.strval($patient->id),
                'type'=>'Patient'
            ],
            "category" =>[
                [
                    "coding" => [
                        [
                            "system"=>"http://terminology.hl7.org/CodeSystem/observation-category",
                            "code"=>"vital-signs",
                            "display"=>"Vital Signs"
                        ]
                    ],
                    "text" => "Vital Signsn"
                ]
            ],





            "component"=> [
                [
                    "code" => [
                        "coding"=> [
                            [
                                "system"=>"http://loinc.org",
                                "code"=>"8480-6",
                                "display"=>"Systolic blood pressure"
                            ],
                        ]
                    ],
                    "valueQuantity" => [
                        "value" =>15,
                        "unit"  => "mm[Hg]",
                        "system" => "http://unitsofmeasure.org",
                        "code" =>  "mm[Hg]",
                    ],
                ],
                [
                    "code" => [
                        "coding"=> [
                            [
                                "system"=>"http://loinc.org",
                                "code"=>"8462-4",
                                "display"=>"Diastolic blood pressure"
                            ],
                        ]
                    ],
                    "valueQuantity" => [
                        "value" =>15,
                        "unit"  => "mm[Hg]",
                        "system" => "http://unitsofmeasure.org",
                        "code" =>  "mm[Hg]",
                    ],
                ],
            ],
            "code"  => [
                "coding" => [
                    [
                        "system"=>"http://loinc.org",
                        "code"=>"85354-9",
                        "display"=>"Blood pressure"
                    ]
                ],
                "text" => "Blood pressure"
            ],
        ]);

        // Provenance of the created Observation
        $this->provenance('Observation/'.$observation->first()->id, 'Observation', $patient);

        // Body weight
        $observation= Observation::factory(1)->create([
            'subject' => [
                'reference'=>'Patient/'.strval($patient->id),
                'type'=>'Patient'
            ],
            "category" =>[
                [
                    "coding" => [
                        [
                            "system"=>"http://terminology.hl7.org/CodeSystem/observation-category",
                            "code"=>"vital-signs",
                            "display"=>"Vital Signs"
                        ]
                    ],
                    "text" => "Vital Signsn"
                ]
            ],
            "code"  => [
                "coding" => [
                    [
                        "system"=>"http://loinc.org",
                        "code"=>"29463-7",
                        "display"=>"Body weight"
                    ]
                ],
                "text" => "Body weight"
            ],
            "valueQuantity" => [
                "value" =>42,
                "unit"  => "kg",
                "system" => "http://unitsofmeasure.org",
                "code" => "kg"
            ]
        ]);

        // Provenance of the created Observation
        $this->provenance('Observation/'.$observation->first()->id, 'Observation', $patient);

        // Heart rate
        $observation= Observation::factory(1)->create([
            'subject' => [
                'reference'=>'Patient/'.strval($patient->id),
                'type'=>'Patient'
            ],
            "category" =>[
                [
                    "coding" => [
                        [
                            "system"=>"http://terminology.hl7.org/CodeSystem/observation-category",
                            "code"=>"vital-signs",
                            "display"=>"Vital Signs"
                        ]
                    ],
                    "text" => "Vital Signsn"
                ]
            ],
            "code"  => [
                "coding" => [
                    [
                        "system"=>"http://loinc.org",
                        "code"=>"8867-4",
                        "display"=>"Heart rate"
                    ]
                ],
                "text" => "Heart rate"
            ],
            "valueQuantity" => [
                "value" =>142,
                "unit"  => "/min",
                "system" => "http://unitsofmeasure.org",
                "code" =>  "/min",
            ]
        ]);

        // Provenance of the created Observation
        $this->provenance('Observation/'.$observation->first()->id, 'Observation', $patient);

        // Respiratory rate
        $observation= Observation::factory(1)->create([
            'subject' => [
                'reference'=>'Patient/'.strval($patient->id),
                'type'=>'Patient'
            ],
            "category" =>[
                [
                    "coding" => [
                        [
                            "system"=>"http://terminology.hl7.org/CodeSystem/observation-category",
                            "code"=>"vital-signs",
                            "display"=>"Vital Signs"
                        ]
                    ],
                    "text" => "Vital Signsn"
                ]
            ],
            "code"  => [
                "coding" => [
                    [
                        "system"=>"http://loinc.org",
                        "code"=>"9279-1",
                        "display"=>"Respiratory rate"
                    ]
                ],
                "text" => "Respiratory rate"
            ],
            "valueQuantity" => [
                "value" =>65,
                "unit"  => "/min",
                "system" => "http://unitsofmeasure.org",
                "code"  => "/min",
            ]
        ]);

        // Provenance of the created Observation
        $this->provenance('Observation/'.$observation->first()->id, 'Observation', $patient);
    }

    private function procedure($patient)
    {
        $procedure= Procedure::factory(1)->create([
            'subject' => [
                'reference'=>'Patient/'.strval($patient->id),
                'type'=>'Patient'
            ],
        ]);

        // Provenance of the created Procedure
        $this->provenance('Procedure/'.$procedure->first()->id, 'Procedure', $patient);

    }

    private function location()
    {
        $location= Location::factory(1)->create()->first();

        $location->update(['identifier'=> [
            [
//                "use"=>"usual",
//                "system" => "http://www.amc.nl/zorgportal/identifiers/visits",
                "value" => strval($location->id)
            ]
        ]]);
    }

    private function extraDocuments()
    {
        $documents= [
            "11488-4"=>"Consult note",
            "18842-5"=>"Discharge summary",
            "34117-2"=>"History and physical note",
            "28570-0"=>"Procedure note",
            "11506-3"=>"Progress note"
        ];

        $patients= Patient::all();

        foreach ($documents as $typeID=>$typeDescription)
        {

            foreach ($patients as $patient)
            {
                $document= DocumentReference::factory(1)->create([
                    'subject' => [
                        'reference'=>'Patient/'.$patient->id,
                        'type'=>'Patient'
                    ],
                    'author'=> [
                        'reference'=>'Practitioner/'.$this->practitionerDefault,//1'
                    ],
                    'type'=> [
                        'coding'=> [
                            [
                                "system"=>"http://loinc.org",
                                "code"=>$typeID,
                                "display"=>$typeDescription
                            ]
                        ]
                    ],
                ])->first();

                $document->update(['identifier'=> [
                    [
                        "use"=>"usual",
                        //"system" => "urn:ietf:rfc:3986",
                        "value" => strval($document->id),
                    ]
                ]]);

            }

        }
    }

    private function extraReports()
    {
        $reports= [
            "LP29708-2"=>"Cardiology",
            "LP7839-6"=>"Pathology"
        ];

        $patients= Patient::all();

        foreach ($reports as $categoryID=>$categoryDescription)
        {

            foreach ($patients as $patient)
            {
                $diagnosticReport= DiagnosticReport::factory(1)->create([
                    'subject' => [
                        'reference'=>'Patient/'.$patient->id,
                        'type'=>'Patient'
                    ],
                    'encounter' => [
                        'reference'=>"Encounter/1",
                        'type'=>'Encounter'
                    ],
                    "performer" => [
                        "reference"=>"Organization/".$this->organizationDefault,
                        'type'=>'Organization'
                    ],
                    "result" => [
                        "reference"=>"Observation/1",
                        'type'=>'Observation'
                    ],
                    'category'=> [
                        "coding" => [
                            [
                                "system"=>"http://loinc.org",
                                "code"=>$categoryID,
                                "display"=>$categoryDescription
                            ]
                        ],
                    ]
                ]);
            }
        }
    }

    private function dataAbsent()
    {

        $patient= Patient::first();
        //$patient->id= 1;

        // Data Absent extension
        $observation= Observation::factory(1)->create([
            'subject' => [
                'reference'=>'Patient/'.$patient->id,
                'type'=>'Patient'
            ],
            "code"=> [
                "coding"=> [
                    [
                        "system"=>"http://loinc.org",
                        "code"=>"72166-2",
                        "display"=>"Tobacco smoking status"
                    ]
                ]
            ],
            "valueCodeableConcept" => [
                "extension"=> [
                    [
                        "url"=> "http://hl7.org/fhir/StructureDefinition/data-absent-reason",
                        "valueCode"=> "unknown"
                ]]
             ]
        ]);

        // Provenance of the created Observation
        $this->provenance('Observation/'.$observation->first()->id, 'Observation', $patient);

        // Data Absent CodeSystem
        $observation= Observation::factory(1)->create([
            'subject' => [
                'reference'=>'Patient/'.$patient->id,
                'type'=>'Patient'
            ],
            "code"=> [
                "coding"=> [
                    [
                        "system"=>"http://loinc.org",
                        "code"=>"72166-2",
                        "display"=>"Tobacco smoking status"
                    ]
                ]
            ],
            "valueCodeableConcept" => [
                "coding"=> [
                    [
                        "system"=>"http://terminology.hl7.org/CodeSystem/data-absent-reason",
                        "code"=>"unknown",
                        "display"=>"Unknown"
                    ]
                ]
             ]
        ]);

        // Provenance of the created Observation
        $this->provenance('Observation/'.$observation->first()->id, 'Observation', $patient);
    }

    public function group()
    {

        // Group #1 for Patients
        $group= Group::factory(1)->create([
            'type' => 'person',
            "member"=> [
                [
                    "entity"=> [
                        'reference'=>'Patient/502',
                        'type'=>'Patient'
                    ]
                ],
                [
                    "entity"=> [
                        'reference'=>'Patient/501',
                        'type'=>'Patient'
                    ]
                ],
                [
                    "entity"=> [
                        'reference'=>'Patient/507',
                        'type'=>'Patient'
                    ]
                ],
                [
                    "entity"=> [
                        'reference'=>'Patient/514',
                        'type'=>'Patient'
                    ]
                ],
            ],
        ]);

        // Group #2 of Patients
        $group= Group::factory(1)->create([
            'type' => 'person',
            "member"=> [
                [
                    "entity"=> [
                        'reference'=>'Patient/504',
                        'type'=>'Patient'
                    ]
                ],
                [
                    "entity"=> [
                        'reference'=>'Patient/502',
                        'type'=>'Patient'
                    ]
                ],
                [
                    "entity"=> [
                        'reference'=>'Patient/508',
                        'type'=>'Patient'
                    ]
                ],
                [
                    "entity"=> [
                        'reference'=>'Patient/517',
                        'type'=>'Patient'
                    ]
                ],
            ],
        ]);

        // Group #1 of Practitioners
        $group= Group::factory(1)->create([
            'type' => 'practitioner',
            "member"=> [
                [
                    "entity"=> [
                        'reference'=>'Practitioner/3',
                        'type'=>'Practitioner'
                    ]
                ],
                [
                    "entity"=> [
                        'reference'=>'Practitioner/7',
                        'type'=>'Practitioner'
                    ]
                ],
                [
                    "entity"=> [
                        'reference'=>'Practitioner/8',
                        'type'=>'Practitioner'
                    ]
                ],
                [
                    "entity"=> [
                        'reference'=>'Practitioner/9',
                        'type'=>'Practitioner'
                    ]
                ],
            ],
        ]);

    }

    public function run()
    {
        //$patients= Patient::factory(20)->create();
        //$patients= Patient::where(['id'=>500]);
        $patients= Patient::factory(1)->create(['id'=>500]);

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
            $this->observation($patient);
            $this->organization();
            $this->practitioner();
            $this->procedure($patient);
            $this->location();
        });




        /* Additional patients for consent management test */

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
            $this->observation($patient);
            $this->organization();
            $this->practitioner();
            $this->procedure($patient);
            $this->location();
        });


        /* End of additional patients */











        $this->medication();
        $this->organizationPayer1();
        $this->extraDocuments();
        $this->extraReports();
        $this->dataAbsent();
        $this->group();

    }
}
