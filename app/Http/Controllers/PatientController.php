<?php

namespace App\Http\Controllers;

use App\Models\Patient;
use Exception;
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


    private function fhirStructure($patient)
    {
        if ($patient!=[])
        {
            $response= [
                "resourceType"=>"Patient",
                "id"=>json_decode($patient->identifier)->value,
                "text"=> [
                    "status"=> "generated",
                    "div"=> "blablabla"
                ],
                "identifier"=> json_decode($patient->identifier),
                "active"=> ($patient->active?true:false),
                "name"=> json_decode($patient->name),
                "telecom"=> json_decode($patient->telecom),
                "gender"=> $patient->gender,
                "birthDate"=> $patient->birthdate,
                "deceasedBoolean"=> $patient->deceasedBoolean,
                "deceasedDateTime"=> $patient->deceasedDateTime,
                "address"=> json_decode($patient->address),
                "maritalStatus"=> json_decode($patient->maritalStatus),
                "contact"=> json_decode($patient->contact),
            ];
        }
        else
            $response=[];

        return $response;
    }

    private function renameParams($params)
    {
        $mapper= ["_id"=>"id"];
        $mapperUnderscore= ["id"=>"id"];
        $output= [];

        foreach ($params as $key=>$value)
        {
            if (substr($key,0,1)=="_")
            {
                $output[$mapperUnderscore[substr($key,1)]]= $value;
            }
            else
            {

                if (in_array($key,$mapper))
                    $output[$mapper[$key]]= $value;
                else
                    $output[$key]= $value;
            }
        }

        return $output;
    }

    private function validParameters($params)
    {
        $valid= ['birthdate','name->text','gender','identifier','_id','_revinclude'];
        $invalid= [];

        foreach ($params as $key=>$value)
        {
            if (!in_array($key,$valid))
                $invalid[]= $key;
        }

        if (count($invalid)>0)
            throw new Exception("Invalid fields: ".implode(", ",$invalid),500);

        return $this->renameParams($params);
    }

    public function index(Request $request)
    {
        /*
        $patients= Patient::whereJsonContains('name->given','Kenny')->inRandomOrder()->limit(1)->get()->toArray();
        print_r($patients);
        die("era");
*/

        $mapper= ["name"=>"name->given","surname"=>"name->family"];
        $mapperUnderscore= ["id"=>"identifier->value"];
        $conditions= [];
        $patients= Patient::query();

        foreach ($request->all() as $key=>$value)
        {
            if (substr($key,0,1)=="_")
                $patients->where($mapperUnderscore[substr($key,1)],'like',$value);
            else
                if (in_array($key,array_keys($mapper)))
                    $patients->where($mapper[$key],'like',$value);
        }

        /*
        $resultado= $patients->inRandomOrder()->limit(100)->get()->toArray();
        print_r($resultado);
        die("pere");
        var_dump($patients);
        //print_r($patients);
        die("per");

        $patients= Patient::whereJsonContains($conditions)->inRandomOrder()->limit(100)->get();
        print_r($patients);
        print_r($conditions);
        die();
*/



        //$patients= Patient::where($this->validParameters(request()->all()))->inRandomOrder()->limit(100)->get();

        //$patients= Patient::where($this->validParameters($conditions))->inRandomOrder()->limit(100)->get();
        $resultado= $patients->inRandomOrder()->limit(100)->get()->toArray();

        if (!$request->wantsJson())
            return response()->xml($patients->limit(100)->get());
        else
            return $patients->limit(100)->get();
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
        //print_r(request()->all('birthDate'));
        //die();

        $patient= [
            'name'=>$arrayRequest['name'][0]['given'][0],
            'active'=>$arrayRequest['active'],
            'surname'=>$arrayRequest['name'][0]['family'],
            'gender'=>$arrayRequest['gender'],
            'birthdate'=>$arrayRequest['birthDate']
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
            'birthdate'=>$arrayRequest['birthDate']
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
