<?php

namespace App\Http\Controllers;

use App\Models\Patient;
use Exception;
use Illuminate\Http\Request;
use Symfony\Component\Console\Input\Input;
use Storage;
use Carbon\Carbon;

use function PHPUnit\Framework\returnSelf;

class PatientController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

    private function filterEmpty(&$array)
    {
        foreach ( $array as $key => $item ) {
            is_array ( $item ) && $array [$key] = $this->filterEmpty( $item );
            if (empty ( $array [$key] ))
                unset ( $array [$key] );
        }
        return $array;
    }


    private function codeGender($gender)
    {
        $return = "";

        switch(strtoupper(trim($gender)))
        {
            case "MALE":
                $return= "M";
                break;

            case "FEMALE":
                $return= "F";
                break;

            case "OTHER":
                $return= "OTHER";
                break;
        }

        return $return;
    }

    private function fhirStructure($patient)
    {
        if ($patient!=[])
        {
            $identifierCast= $patient->identifier;
            $identifierCast['value']= strval($identifierCast['value']);

            $deceased= null;
            if ($patient->deceasedDateTime!=null)
                $deceased= Carbon::parse($patient->deceasedDateTime)->toIso8601String();

            $response= [
                "resourceType"=>"Patient",
                "id"=>strval($patient->identifier['value']),
                "text"=> [
                    "status"=> "generated",
                    "div"=> "<div xmlns=\"http://www.w3.org/1999/xhtml\">Success!</div>"
                ],
                "identifier"=> [$identifierCast],
                "active"=> ($patient->active?true:false),
                "name"=> [$patient->name],
                "telecom"=> $patient->telecom,
                "gender"=> $patient->gender,
                "birthDate"=> $patient->birthDate,
                //"deceasedBoolean"=> ($patient->deceasedBoolean?true:false),
                "deceasedDateTime"=> $deceased,
                "address"=> [$patient->address],
                "maritalStatus"=> $patient->maritalStatus,
                "contact"=> [$patient->contact],
                "communication"=> [$patient->communication],
                // Hardcoded information
                "extension"=> [
                    [
                        "extension"=>[
                            [
                                "url"=>"ombCategory",
                                "valueCoding"=> [
                                    "system" =>"urn:oid:2.16.840.1.113883.6.238",
                                    "code" =>"2106-3",
                                    "display"=> "White"
                                ]
                            ],
                            [
                                "url"=>"text",
                                "valueString"=>"White"
                            ]
                        ],
                        "url"=>"http://hl7.org/fhir/us/core/StructureDefinition/us-core-race"
                    ],
                    [
                        "url"=>"http://hl7.org/fhir/us/core/StructureDefinition/us-core-birthsex",
                        "valueCode"=>$this->codeGender($patient->gender)
                    ],
                    [
                        "extension"=>[
                            [
                                "url"=>"ombCategory",
                                "valueCoding"=> [
                                    "system" =>"urn:oid:2.16.840.1.113883.6.238",
                                    "code" =>"2186-5",
                                    "display"=> "Not Hispanic or Latino"
                                ]
                            ],
                            [
                                "url"=>"text",
                                "valueString"=>"Not Hispanic or Latino"
                            ]
                        ],
                        "url"=>"http://hl7.org/fhir/us/core/StructureDefinition/us-core-ethnicity"
                    ],
                    [
                        "url"=>"http://hl7.org/fhir/StructureDefinition/patient-genderIdentity",
                        "valueCodeableConcept" => $patient->genderIdentity
                    ]

                ],

            ];

            $response= $this->filterEmpty($response);

        }
        else
            $response=[];

        return $response;
    }

    private function mapperToEloquent(&$query,$options,$value)
    {
        $query->where(function ($element) use ($options,$value) {
            foreach ($options as $option)
                $element->orWhere($option,'=',$value);
        });
    }

    public function index(Request $request)
    {

        $mapper= [
            "name"=>["name->text","name->family","name->given"],
            "surname"=>["name->family"],
            "id"=>["identifier->value"],
            "birthdate"=>["birthdate"],
            "gender"=>["gender"],
            "identifier"=>["identifier->value"]
        ];
        $mapperUnderscore= [
            "id"=>"id"];
        $patients= Patient::query();

        foreach ($request->all() as $key=>$value)
        {
            $key= strtolower($key);
            // Methods with underscore
            if (substr($key,0,1)=="_")
            {
                $patients->where($mapperUnderscore[substr($key,1)],'=',$value);
            }
            else
            {
                if (in_array($key,array_keys($mapper)))
                {
                    if ($key=="identifier")
                    {
                        if (strpos($value,"|")>0)
                        {
                            $explodeValue= explode('|',$value);
                            $patients->where('identifier->system','=',$explodeValue[0]);
                            $patients->where('identifier->value','=',$explodeValue[1]);
                        }
                        else
                            $this->mapperToEloquent($patients,$mapper[$key],$value);
                            //$patients->where($mapper[$key],'=',$value);

                    }
                    else
                        $this->mapperToEloquent($patients,$mapper[$key],$value);
                        //$patients->where($mapper[$key],'=',$value);
                }
            }
        }

        if (!$request->wantsJson())
            return response()->xml($patients->limit(100)->get());
        else
        {
            $finalResponse= [];
            //DB::enableQueryLog();
            foreach ($patients->limit(100)->get() as $pat)
            {
                $response= $this->fhirStructure($pat);
                $finalResponse[]= $response;
            }

            //print_r(DB::getQueryLog());
            ///die();

            return $finalResponse;
            //return $patients->limit(100)->get();
        }


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
    public function create(Request $request)
    {
        //
        print_r($request);
        Storage::put("public/dumpCreate.txt",print_r($request,true));
        die();
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {


        $arrayRequest= $request->toArray();

        $patient= [
            'name'=>$arrayRequest['name'][0]['given'][0],
            'active'=>$arrayRequest['active'],
            'surname'=>$arrayRequest['name'][0]['family'],
            'gender'=>$arrayRequest['gender'],
            'birthDate'=>$arrayRequest['birthDate']
        ];


//        Storage::put("public/dumpCreate.txt",dump($request));
        $patientNew= Patient::create($patient);
        return $this->fhirStructure($patientNew);
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
        $response= $this->fhirStructure($patient);
        $finalResponse= ["resource"=>$response];

        return $finalResponse;
    }

    public function tenancytest($tenantID,$patientID)
    {

        if ($tenantID==1)
            $patient= Patient::findOrFail($patientID);
        else
            $patient= array();

        $response= $this->fhirStructure($patient);
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
        die("editing");
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

        $arrayRequest= request()->toArray();

        $patientTemp= [
            'name'=>$arrayRequest['name'][0]['given'][0],
            'active'=>$arrayRequest['active'],
            'surname'=>$arrayRequest['name'][0]['family'],
            'gender'=>$arrayRequest['gender'],
            'birthDate'=>$arrayRequest['birthDate']
        ];

        $patient->fill($patientTemp);

//        Storage::put("public/dumpCreate.txt",dump($request));
        $patientUpdated= $patient->save();

        return $this->fhirStructure($patientUpdated);

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
        $patients= Patient::where($request->all())->get();
        $return= [];

        foreach($patients as $patient)
        {
            $response= $this->fhirStructure($patient);
            $return["resource"][]= $response;
//            $finalResponse= ["resource"=>$response];
        }

        return $return;
    }
}
