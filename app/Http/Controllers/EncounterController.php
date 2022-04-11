<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Encounter;


class EncounterController extends Controller
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

    private function fhirStructure($encounter)
    {
        if ($encounter!=[])
        {
            $response= [
                'resourceType'=>"Encounter",
                'id'=>strval($encounter->id),
                "text"=> [
                    "status"=> "generated",
                    "div"=> "<div xmlns=\"http://www.w3.org/1999/xhtml\">Success!</div>"
                ],
                'identifier' => [$encounter->identifier],
                'status' => $encounter->status,
                'class' => $encounter->class,
                'classHistory' => [$encounter->classHistory],
                'type' => [$encounter->type],
                'serviceType' => [$encounter->serviceType],
                'priority' => [$encounter->priority],
                'subject' => $encounter->subject,
                'episodeOfCare' => [$encounter->episodeOfCare],
                'basedOn' => [$encounter->basedOn],
                'participant' => [$encounter->participant],
                'appointment' => [$encounter->appointment],
                'period' => [$encounter->period],
                'length' => [$encounter->length],
                'reasonCode' => [$encounter->reasonCode],
                'reasonReference' => [$encounter->reasonReference],
                'diagnosis' => [$encounter->diagnosis],
                'account' => [$encounter->account],
                'hospitalization' => [$encounter->hospitalization],
                'location' => [$encounter->location],
                'serviceProvider' => [$encounter->serviceProvider],
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
            "patient"=>["subject->reference"],
            //"status"=>["status"]

        ];
        $mapperUnderscore= [
            "id"=>"id",
        ];
        $encounters= [];
        $encounters= Encounter::query();

        foreach ($request->all() as $key=>$value)
        {
            $key= strtolower($key);
            // Methods with underscore
            if (substr($key,0,1)=="_")
            {
                $encounters->where($mapperUnderscore[substr($key,1)],'=',$value);
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
                            $encounters->where('identifier->system','=',$explodeValue[0]);
                            $encounters->where('identifier->value','=',$explodeValue[1]);
                        }
                        else
                            $this->mapperToEloquent($encounters,$mapper[$key],$value);

                    }
                    else
                        $this->mapperToEloquent($encounters,$mapper[$key],$value);
                }
            }
        }

        if (!$request->wantsJson())
            return response()->xml($encounters->limit(100)->get());
        else
        {
            $finalResponse= [];
            foreach ($encounters->limit(100)->get() as $pat)
            {
                $response= $this->fhirStructure($pat);
                $finalResponse[]= $response;
            }

            return $finalResponse;
        }


    }

    public function show($encounterID)
    {
        $encounter= Encounter::findOrFail($encounterID);
        $response= $this->fhirStructure($encounter);
        $finalResponse= ["resource"=>$response];

        return $finalResponse;
    }

}
