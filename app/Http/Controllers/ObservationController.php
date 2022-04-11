<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Observation;

class ObservationController extends Controller
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

    private function fhirStructure($observation)
    {
        if ($observation!=[])
        {
            $response= [
                'resourceType'=>"Observation",
                //'id'=>strval($observation->id),
                'id'=>strval($observation->id),
                "text"=> [
                    "status"=> "generated",
                    "div"=> "<div xmlns=\"http://www.w3.org/1999/xhtml\">Success!</div>"
                ],
                'identifier' => $observation->identifier,
                'basedOn' => $observation->basedOn,
                'partOf' => $observation->partOf,
                'status' => $observation->status,
                'category' => $observation->category,
                'code' => $observation->code,
                'subject' => $observation->subject,
                'focus' => $observation->focus,
                'encounter' => $observation->encounter,
                'effectiveDateTime' => $observation->effectiveDateTime,
                'effectivePeriod' => $observation->effectivePeriod,
                'effectiveTiming' => $observation->effectiveTiming,
                'effectiveInstant' => $observation->effectiveInstant,
                'issued' => $observation->issued,
                'performer' => $observation->performer,
                'valueQuantity' => $observation->valueQuantity,
                'valueCodeableConcept' => $observation->valueCodeableConcept,
                'valueString' => $observation->valueString,
                'valueBoolean' => $observation->valueBoolean,
                'valueInteger' => strval($observation->valueInteger),
                'valueRange' => $observation->valueRange,
                'valueRatio' => $observation->valueRatio,



                'valueSampledData' => $observation->valueSampledData,
                'valueTime' => $observation->valueTime,
                'valueDateTime' => $observation->valueDateTime,
                'valuePeriod' => $observation->valuePeriod,
                'dataAbsentReason' => $observation->dataAbsentReason,
                'interpretation' => $observation->interpretation,
                'note' => $observation->note,
                'bodySite' => $observation->bodySite,
                'method' => $observation->method,
                'specimen' => $observation->specimen,
                'device' => $observation->device,
                'referenceRange' => $observation->referenceRange,
                'hasMember' => $observation->hasMember,
                'derivedFrom' => $observation->derivedFrom,
                'component' => $observation->component,
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
            "intent"=>["intent"],
            "patient"=>["subject->reference"],
        ];
        $mapperUnderscore= [
            "id"=>"id",
        ];
        $observations= [];
        $observations= Observation::query();

        foreach ($request->all() as $key=>$value)
        {
            $key= strtolower($key);
            // Methods with underscore
            if (substr($key,0,1)=="_")
            {
                $observations->where($mapperUnderscore[substr($key,1)],'=',$value);
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
                            $observations->where('identifier->system','=',$explodeValue[0]);
                            $observations->where('identifier->value','=',$explodeValue[1]);
                        }
                        else
                            $this->mapperToEloquent($observations,$mapper[$key],$value);

                    }
                    else
                    {
                        if ($key=="patient")
                        {
                            if (strpos($value,"/")>0)
                            {
                                die("ere");
                                $explodeValue= explode('/',$value);
                                $this->mapperToEloquent($observations,$mapper[$key],$explodeValue[1]);
                            }
                            else
                                $this->mapperToEloquent($observations,$mapper[$key],"Patient/".$value);
                        }
                        else
                            $this->mapperToEloquent($observations,$mapper[$key],$value);

                    }
                }
            }
        }

        if (!$request->wantsJson())
            return response()->xml($observations->limit(100)->get());
        else
        {
            $finalResponse= [];
            foreach ($observations->limit(100)->get() as $pat)
            {
                $response= $this->fhirStructure($pat);
                $finalResponse[]= $response;
            }

            return $finalResponse;
        }


    }

    public function show($observationID)
    {
        $observation= Observation::orWhere(['id'=>$observationID])->orWhereJsonContains('identifier',['value'=>$observationID])->first();
//        $documentreferences->whereJsonContains('code',['coding'=>['code'=>$explodeValue[1]]]);

        if ($observation==null)
            throw new NotFoundResourceException();

        $response= $this->fhirStructure($observation);
        $finalResponse= ["resource"=>$response];

        return $finalResponse;
    }
}
