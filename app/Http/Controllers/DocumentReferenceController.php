<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use App\Models\DocumentReference;

class DocumentReferenceController extends Controller
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

    private function fhirStructure($documentreference)
    {
        if ($documentreference!=[])
        {
            $response= [
                'resourceType'=>"DocumentReference",
                'id'=>strval($documentreference->id),
                "text"=> [
                    "status"=> "generated",
                    "div"=> "<div xmlns=\"http://www.w3.org/1999/xhtml\">Success!</div>"
                ],
                'identifier' => $documentreference->identifier,
                'basedOn' => $documentreference->basedOn,
                'status' => $documentreference->status,
                'docStatus' => $documentreference->docStatus,
                'type'=> [$documentreference->type],
                'category'=> [$documentreference->category],
                'subject'=> [$documentreference->subject],
                'encounter' => [$documentreference->encounter],
                'event' => [$documentreference->event],
                'facilityType' => [$documentreference->facilityType],
                'practiceSetting' => [$documentreference->practiceSetting],
                'period' => [$documentreference->period],
                'date'=>$documentreference->date,
                'author' => [$documentreference->author],
                'attester'  => [$documentreference->attester],
                'custodian' => [$documentreference->custodian],
                'relatesTo' => [$documentreference->relatesTo],
                'description' => [$documentreference->description],
                'securityLabel' => [$documentreference->securityLabel],
                'content' => [$documentreference->content],
                'context' => [$documentreference->context],
                'sourcePatientInfo' => [$documentreference->sourcePatientInfo],
                'related' => [$documentreference->related],
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
            "category"=>["category->coding->code"],
            "code"=>["code->coding->code"],
            //"status"=>["status"]

        ];
        $mapperUnderscore= [
            "id"=>"id",
        ];
        $documentreferences= [];
        $documentreferences= DocumentReference::query();

        foreach ($request->all() as $key=>$value)
        {
            $key= strtolower($key);
            // Methods with underscore
            if (substr($key,0,1)=="_")
            {
                $documentreferences->where($mapperUnderscore[substr($key,1)],'=',$value);
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
                                $documentreferences->where('identifier->system','=',$explodeValue[0]);
                                $documentreferences->where('identifier->value','=',$explodeValue[1]);
                            }
                            else
                                $this->mapperToEloquent($documentreferences,$mapper[$key],$value);
                            break;

                        case "category":
                            if (strpos($value,"|")>0)
                            {
                                $explodeValue= explode('|',$value);
                                $documentreferences->where('category->coding->system','=',$explodeValue[0]);
                                $documentreferences->where('category->coding->code','=',$explodeValue[1]);
                            }
                            else
                                $this->mapperToEloquent($documentreferences,$mapper[$key],$value);

                            break;

                        case "code":
                            if (strpos($value,"|")>0)
                            {
                                $explodeValue= explode('|',$value);
                                $documentreferences->whereJsonContains('code',['coding'=>['system'=>$explodeValue[0]]]);
                                $documentreferences->whereJsonContains('code',['coding'=>['code'=>$explodeValue[1]]]);
//                                $documentreferences->where('code->coding->system','=',$explodeValue[0]);
//                                $documentreferences->where('code->coding->code','=',$explodeValue[1]);
                            }
                            else
                                $documentreferences->whereJsonContains('code',['coding'=>['code'=>$value]]);
                            break;

                            case "patient":
                                if (strpos($value,"/")>0)
                                {
                                    $explodeValue= explode('/',$value);
                                    $this->mapperToEloquent($documentreferences,$mapper[$key],"Patient/".$explodeValue[1]);
                                }
                                else
                                    $this->mapperToEloquent($documentreferences,$mapper[$key],"Patient/".$value);
                                break;


                        default:
                            $this->mapperToEloquent($documentreferences,$mapper[$key],$value);
                            break;
                    }
                }
            }
        }

        if (!$request->wantsJson())
            return response()->xml($documentreferences->limit(100)->get());
        else
        {
            // Your Eloquent query executed by using get()

            $finalResponse= [];
            foreach ($documentreferences->limit(100)->get() as $pat)
            {
                $response= $this->fhirStructure($pat);
                $finalResponse[]= $response;
            }

            return $finalResponse;
        }


    }

    public function show($allergyIntoleranceID)
    {
        $documentreference= DocumentReference::findOrFail($allergyIntoleranceID);
        $response= $this->fhirStructure($documentreference);
        $finalResponse= ["resource"=>$response];

        return $finalResponse;
    }
}
