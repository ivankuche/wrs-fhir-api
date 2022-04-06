<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Provenance;

class ProvenanceController extends Controller
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

    private function fhirStructure($provenance)
    {
        if ($provenance!=[])
        {
            $response= [
                "resourceType"=>"Provenance",
                "id"=>$provenance->id,
                "target"=> [$provenance->target],
                "occurredPeriod"=> [$provenance->occurredPeriod],
                "occurredDateTime"=> $provenance->occurredDateTime,
                "recorded"=> $provenance->recorded,
                "policy"=> $provenance->policy,
                "location"=>$provenance->location,
                "authorization" => $provenance->location,
                "activity" => $provenance->activity,
                "basedOn" => $provenance->basedOn,
                "patient" => $provenance->patient,
                "encounter" => $provenance->encounter,
                "agent" => $provenance->agent,
                "entity" => $provenance->entity,
                "signature" => $provenance->signature,
            ];

            $response= $this->filterEmpty($response);
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

    private function mapperToEloquent(&$query,$options,$value)
    {
        $query->where(function ($element) use ($options,$value) {
            foreach ($options as $option)
                $element->orWhere($option,'=',$value);
        });
    }

    private function evaluateMapper($value)
    {
        $return= null;
        switch ($value)
        {

        }

        return $return;
    }

    public function index(Request $request)
    {

        $mapper= ["target"=>"CUSTOM_LOGIC",];
        $mapperUnderscore= [
            "id"=>"id",
        ];
        $conditions= [];
        $provenances= Provenance::query();

        foreach ($request->all() as $key=>$value)
        {
            $key= strtolower($key);
            // Methods with underscore
            if (substr($key,0,1)=="_")
            {
                $provenances->where($mapperUnderscore[substr($key,1)],'=',$value);
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
                            $provenances->where('identifier->system','=',$explodeValue[0]);
                            $provenances->where('identifier->value','=',$explodeValue[1]);
                        }
                        else
                            $this->mapperToEloquent($provenances,$mapper[$key],strtolower($value));
                            //$provenances->where($mapper[$key],'=',$value);

                    }
                    else
                    {
                        if ($key=="target")
                        {
                            $target= explode("/",$value);
                            $arrayTargets= [
                                'patient' => ["patient->reference"],
                                'allergyintolerance' => ['target->reference']
                            ];

                            $this->mapperToEloquent($provenances,$arrayTargets[strtolower($target[0])],$value);
                        }
                        else
                            $this->mapperToEloquent($provenances,$mapper[$key],strtolower($value));
                    }
                        //$provenances->where($mapper[$key],'=',$value);
                }
            }
        }

        if (!$request->wantsJson())
            return response()->xml($provenances->limit(100)->get());
        else
        {
            $finalResponse= [];
            //DB::enableQueryLog();
            foreach ($provenances->limit(100)->get() as $pat)
            {
                $response= $this->fhirStructure($pat);
                $finalResponse[]= $response;
            }

            //print_r(DB::getQueryLog());
            ///die();

            return $finalResponse;
            //return $provenances->limit(100)->get();
        }


    }

    public function pagination(Request $request)
    {
        $provenances= Provenance::inRandomOrder()->paginate(5);//->get();


        if (!$request->wantsJson())
            return response()->xml($provenances);
        else
            return $provenances;
    }

    public function getmodified(Request $request)
    {
        $provenances= Provenance::inRandomOrder()->limit(100)->get();

        foreach ($provenances as $id=>$data)
        {
            $provenances[$id]['nationality']='USA';
        }


        if (!$request->wantsJson())
            return response()->xml($provenances);
        else
            return $provenances;
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
        $patientNew= Provenance::create($patient);
        return $this->fhirStructure($patientNew);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Provenance  $patient
     * @return \Illuminate\Http\Response
     */
    public function show($provenanceID)
    {
        $provenance= Provenance::findOrFail($provenanceID);
        $response= $this->fhirStructure($provenance);
        $finalResponse= ["resource"=>$response];

        return $finalResponse;
    }

    public function tenancytest($tenantID,$patientID)
    {

        if ($tenantID==1)
            $patient= Provenance::findOrFail($patientID);
        else
            $patient= array();

        $response= $this->fhirStructure($patient);
        $finalResponse= ["resource"=>$response];

        return $finalResponse;

    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Provenance  $patient
     * @return \Illuminate\Http\Response
     */
    public function edit(Provenance $patient)
    {
        die("editing");
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Provenance  $patient
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Provenance $patient)
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
     * @param  \App\Models\Provenance  $patientnereka
     * @return \Illuminate\Http\Response
     */
    public function destroy(Provenance $patient)
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
            return response()->xml($provenances);
        else
            return $provenances;

        // url will be: http://my.domain.com/test.php?key1=5&key2=ABC;

        $statusCode = $response->getStatusCode();
        $content = $response->getBody();

        die($content);
        */

    }

    public function search(Request $request)
    {
        $provenances= Provenance::where($request->all())->get();
        $return= [];

        foreach($provenances as $patient)
        {
            $response= $this->fhirStructure($patient);
            $return["resource"][]= $response;
//            $finalResponse= ["resource"=>$response];
        }

        return $return;
    }

}
