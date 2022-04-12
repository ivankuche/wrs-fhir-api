<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Observation;
use Carbon\Carbon;

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
                'effectiveDateTime' => Carbon::parse($observation->effectiveDateTime)->toIso8601String(),
                'effectivePeriod' => $observation->effectivePeriod,
                'effectiveTiming' => $observation->effectiveTiming,
                'effectiveInstant' => $observation->effectiveInstant,
                'issued' => Carbon::parse($observation->issued)->toIso8601String(),
                'performer' => $observation->performer,
                'valueQuantity' => $observation->valueQuantity,
                'valueCodeableConcept' => $observation->valueCodeableConcept,
                'valueString' => $observation->valueString,
                'valueBoolean' => $observation->valueBoolean,
                'valueInteger' => $observation->valueInteger,
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
            "code"=>["code"],
            "category"=>["category"],
            "patient"=>["subject->reference"],
            "date"=>["effectiveDateTime"]
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
                    switch ($key)
                    {
                        case "identifier":
                            if (strpos($value,"|")>0)
                            {
                                $explodeValue= explode('|',$value);
                                $observations->where('identifier->system','=',$explodeValue[0]);
                                $observations->where('identifier->value','=',$explodeValue[1]);
                            }
                            else
                                $this->mapperToEloquent($observations,$mapper[$key],$value);
                            break;

                        case "category":
                            if (strpos($value,"|")>0)
                            {
                                $explodeValue= explode('|',$value);
                                $observations->whereJsonContains('category',['coding'=>['system'=>$explodeValue[0]]]);
                                $observations->whereJsonContains('category',['coding'=>['code'=>$explodeValue[1]]]);
                            }
                            else
                            $observations->whereJsonContains('category',['coding'=>['code'=>$value]]);

                            break;

                        case "code":
                            if (strpos($value,"|")>0)
                            {
                                $explodeValue= explode('|',$value);
                                $observations->whereJsonContains('code',['coding'=>['system'=>$explodeValue[0]]]);
                                $observations->whereJsonContains('code',['coding'=>['code'=>$explodeValue[1]]]);
                            }
                            else
                                $observations->whereJsonContains('code',['coding'=>['code'=>$value]]);
                            break;

                        case "patient":
                            if (strpos($value,"/")>0)
                            {
                                $explodeValue= explode('/',$value);
                                $this->mapperToEloquent($observations,$mapper[$key],$explodeValue[1]);
                            }
                            else
                                $this->mapperToEloquent($observations,$mapper[$key],"Patient/".$value);
                        break;

                        case "date":

                            $evaluator= "=";
                            switch (substr($value,0,2))
                            {
                                case "gt":
                                    $evaluator= ">";
                                    break;
                            }

                            $observations->where('effectiveDateTime',$evaluator,date('Y-m-d H:i:s',strtotime(substr($value,2))));
                            break;

                        default:
                            $this->mapperToEloquent($observations,$mapper[$key],$value);
                            break;
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
