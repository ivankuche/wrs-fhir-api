<?php

namespace App\Http\Controllers;

use App\Models\Patient;
use Illuminate\Http\Request;
use Symfony\Component\Console\Input\Input;
use Storage;

use function PHPUnit\Framework\returnSelf;

class PatientController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

    public function index(Request $request)
    {

        $patients= Patient::inRandomOrder()->limit(100)->get();


        if (!$request->wantsJson())
            return response()->xml($patients);
        else
            return $patients;
    }

    public function pagination(Request $request)
    {
        $patients= Patient::inRandomOrder()->paginate(5);//->get();


        if (!$request->wantsJson())
            return response()->xml($patients);
        else
            return $patients;
    }

    public function getmodified(Request $request)
    {
        $patients= Patient::inRandomOrder()->limit(100)->get();

        foreach ($patients as $id=>$data)
        {
            $patients[$id]['nationality']='USA';
        }


        if (!$request->wantsJson())
            return response()->xml($patients);
        else
            return $patients;
    }


    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
        die("para");
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        return Patient::create(request()->all());
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Patient  $patient
     * @return \Illuminate\Http\Response
     */
    public function show($patientID)
    {
        $patient= Patient::findOrFail($patientID);
        $response= [
            "resourceType"=>"Patient",
            "id"=>$patient->id,
            "text"=> [
              "status"=> "generated",
              "div"=> "generated"
            ],
            "identifier"=> [
              [
                "use"=> "usual",
                "type"=> [
                  "coding"=> [
                    [
                      "system"=> "http://terminology.hl7.org/CodeSystem/v2-0203",
                      "code"=> "MR"
                    ]
                  ]
                ],
                "system"=>"urn:oid:1.2.36.146.595.217.0.1",
                "value"=> "12345",
                "period"=> [
                  "start"=> "2001-05-06"
                ],
                "assigner"=> [
                  "display"=> "Acme Healthcare"
                ]
              ]
            ],
            "active"=> ($patient->active?true:false),
            "name"=> [
              [
                "use"=> "official",
                "family"=> $patient->surname,
                "given"=> [
                  $patient->name
                ]
              ],
              [
                "use"=> "usual",
                "given"=> [
                  $patient->name
                ]
              ],
              [
                "use"=> "maiden",
                "family"=> "Windsor",
                "given"=> [
                  "Peter",
                  "James"
                ],
                "period"=> [
                  "end"=> "2002"
                ]
              ]
            ],
            "telecom"=> [
              [
                "use"=> "home"
              ],
              [
                "system"=> "phone",
                "value"=> "(03) 5555 6473",
                "use"=> "work",
                "rank"=> 1
              ],
              [
                "system"=> "phone",
                "value"=> "(03) 3410 5613",
                "use"=> "mobile",
                "rank"=> 2
              ],
              [
                "system"=> "phone",
                "value"=> "(03) 5555 8834",
                "use"=> "old",
                "period"=> [
                  "end"=> "2014"
                ]
              ]
            ],
            "gender"=> $patient->gender,
            "birthDate"=> $patient->birthdate,
            "_birthDate"=> [
              "extension"=> [
                [
                  "url"=> "http://hl7.org/fhir/StructureDefinition/patient-birthTime",
                  "valueDateTime"=> "1974-12-25T14:35:45-05:00"
                ]
              ]
            ],
            "deceasedBoolean"=> false,
            "address"=> [
              [
                "use"=> "home",
                "type"=> "both",
                "text"=> "534 Erewhon St PeasantVille, Rainbow, Vic  3999",
                "line"=> [
                  "534 Erewhon St"
                ],
                "city"=> "PleasantVille",
                "district"=> "Rainbow",
                "state"=> "Vic",
                "postalCode"=> "3999",
                "period"=> [
                  "start"=> "1974-12-25"
                ]
              ]
            ],
            "contact"=> [
              [
                "relationship"=> [
                  [
                    "coding"=> [
                      [
                        "system"=> "http://terminology.hl7.org/CodeSystem/v2-0131",
                        "code"=> "N"
                      ]
                    ]
                  ]
                ],
                "name"=> [
                  "family"=> "du Marché",
                  "_family"=> [
                    "extension"=> [
                      [
                        "url"=> "http://hl7.org/fhir/StructureDefinition/humanname-own-prefix",
                        "valueString"=> "VV"
                      ]
                    ]
                  ],
                  "given"=> [
                    "Bénédicte"
                  ]
                ],
                "telecom"=> [
                  [
                    "system"=> "phone",
                    "value"=> "+33 (237) 998327"
                  ]
                ],
                "address"=> [
                  "use"=> "home",
                  "type"=> "both",
                  "line"=> [
                    "534 Erewhon St"
                  ],
                  "city"=> "PleasantVille",
                  "district"=> "Rainbow",
                  "state"=> "Vic",
                  "postalCode"=> "3999",
                  "period"=> [
                    "start"=> "1974-12-25"
                  ]
                ],
                "gender"=> "female",
                "period"=> [
                  "start"=> "2012"
                ]
              ]
            ],
            "managingOrganization"=> [
              "reference"=> "Organization/1"
            ]
            ];

        $finalResponse= ["resource"=>$response];

        return $finalResponse;
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Patient  $patient
     * @return \Illuminate\Http\Response
     */
    public function edit(Patient $patient)
    {
        die("nerek");
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Patient  $patient
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Patient $patient)
    {
        $patient->update($request->all());
        return $patient;
                //
        //die("nereka");
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Patient  $patientnereka
     * @return \Illuminate\Http\Response
     */
    public function destroy(Patient $patient)
    {
        //
        die("pearaer");
    }

    public function recursive()
    {

        $type= ['Accept'=>'application/xml'];
        if (request()->wantsJson())
            $type= ['Accept'=>'application/json'];

        $endpoint = "http://wrs.test/patient";
        $client = new \GuzzleHttp\Client(['base_uri'=>$endpoint,'headers'=>$type]);


        $response = $client->request('GET');//, $endpoint);
        return $response->getBody();

        /*
        if (!$request->wantsJson())
            return response()->xml($patients);
        else
            return $patients;

        // url will be: http://my.domain.com/test.php?key1=5&key2=ABC;

        $statusCode = $response->getStatusCode();
        $content = $response->getBody();

        die($content);
        */

    }

    public function search(Request $request)
    {
        $patients= Patient::where($request->all())->get()->toArray();
        return $patients;
    }
}
