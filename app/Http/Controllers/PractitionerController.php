<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Practitioner;
use DB;

class PractitionerController extends Controller
{
    private function filterEmpty(&$array)
    {
        foreach ( $array as $key => $item ) {
            is_array ( $item ) && $array [$key] = $this->filterEmpty( $item );
            if (empty ( $array [$key] ))
                unset ( $array [$key] );
        }
        return $array;
    }

    private function fhirStructure($practitioner)
    {
        if ($practitioner!=[])
        {
            $identifiers= $practitioner->identifier;
            foreach ($identifiers as $key=>$identifier)
                $identifiers[$key]['value']= strval($identifiers[$key]['value']);

            $response= [
                'resourceType'=>"Practitioner",
                'id'=>strval($practitioner->id),
                "text"=> [
                    "status"=> "generated",
                    "div"=> "<div xmlns=\"http://www.w3.org/1999/xhtml\">Success!</div>"
                ],
                "identifier"=> $identifiers,
                "active"=> ($practitioner->active?true:false),
                "name"=> [$practitioner->name],
                "telecom"=> $practitioner->telecom,
                "address"=> [$practitioner->address],
                "gender"=> $practitioner->gender,
                "birthDate"=> $practitioner->birthDate,
                "photo"=> $practitioner->birthDate,
                "qualification" => $practitioner->qualification,
                "communication" => $practitioner->communication,
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
            {
                if (strpos($value,',')>0)
                {
                    $values= explode(',',$value);
                    $element->orWhereIn($option,$values);
                }
                else
                    $element->orWhere($option,'=',$value);

            }
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
        $practitioners= Practitioner::query();

        foreach ($request->all() as $key=>$value)
        {
            $key= strtolower($key);
            // Methods with underscore
            if (substr($key,0,1)=="_")
            {
                $practitioners->where($mapperUnderscore[substr($key,1)],'=',$value);
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
                            $practitioners->where('identifier->system','=',$explodeValue[0]);
                            $practitioners->where('identifier->value','=',$explodeValue[1]);
                        }
                        else
                            $this->mapperToEloquent($practitioners,$mapper[$key],$value);

                    }
                    else
                        $this->mapperToEloquent($practitioners,$mapper[$key],$value);
                }
            }
        }

        if (!$request->wantsJson())
            return response()->xml($practitioners->limit(100)->get());
        else
        {
            $finalResponse= [];
            foreach ($practitioners->limit(100)->get() as $pat)
            {
                $response= $this->fhirStructure($pat);
                $finalResponse[]= $response;
            }
            return $finalResponse;
        }


    }

    public function show($allergyIntoleranceID)
    {
        $practitioner= Practitioner::findOrFail($allergyIntoleranceID);
        $response= $this->fhirStructure($practitioner);
        $finalResponse= ["resource"=>$response];

        return $finalResponse;
    }

}
