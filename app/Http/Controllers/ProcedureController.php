<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Carbon\Carbon;
use App\Models\Procedure;

class ProcedureController extends Controller
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

    private function fhirStructure($procedure)
    {
        if ($procedure!=[])
        {
            $response= [
                'resourceType'=>"Procedure",
                //'id'=>strval($procedure->id),
                'id'=>strval($procedure->id),
                "text"=> [
                    "status"=> "generated",
                    "div"=> "<div xmlns=\"http://www.w3.org/1999/xhtml\">Success!</div>"
                ],
                'identifier' => $procedure->identifier,
                'instantiatesUri' => $procedure->instantiatesUri,
                'basedOn' => $procedure->basedOn,
                'partOf' => $procedure->partOf,
                'status' => $procedure->status,
                'statusReason' => $procedure->statusReason,
                'category' => $procedure->category,
                'code' => $procedure->code,
                'subject' => $procedure->subject,
                'encounter' => $procedure->encounter,
                'performedDateTime' => Carbon::parse($procedure->performedDateTime)->toIso8601String(),
                'performedPeriod' => $procedure->performedPeriod,
                'performedString' => $procedure->performedString,
                'performedAge' => $procedure->performedAge,
                'performedRange' => $procedure->performedRange,
                'recorder' => $procedure->recorder,
                'asserter' => $procedure->asserter,
                'performer' => $procedure->performer,
                'location' => $procedure->location,
                'reasonCode' => $procedure->reasonCode,
                'reasonReference' => $procedure->reasonReference,
                'bodySite' => $procedure->bodySite,
                'outcome' => $procedure->outcome,
                'report' => $procedure->report,
                'complication' => $procedure->complication,
                'complicationDetail' => $procedure->complicationDetail,
                'followUp' => $procedure->followUp,
                'note' => $procedure->note,
                'focalDevice' => $procedure->focalDevice,
                'usedReference' => $procedure->usedReference,
                'usedCode' => $procedure->usedCode,
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
        $procedures= [];
        $procedures= Procedure::query();

        foreach ($request->all() as $key=>$value)
        {
            $key= strtolower($key);
            // Methods with underscore
            if (substr($key,0,1)=="_")
            {
                $procedures->where($mapperUnderscore[substr($key,1)],'=',$value);
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
                                $procedures->where('identifier->system','=',$explodeValue[0]);
                                $procedures->where('identifier->value','=',$explodeValue[1]);
                            }
                            else
                                $this->mapperToEloquent($procedures,$mapper[$key],$value);
                            break;

                        case "category":
                            if (strpos($value,"|")>0)
                            {
                                $explodeValue= explode('|',$value);
                                $procedures->whereJsonContains('category',['coding'=>['system'=>$explodeValue[0]]]);
                                $procedures->whereJsonContains('category',['coding'=>['code'=>$explodeValue[1]]]);
                            }
                            else
                            $procedures->whereJsonContains('category',['coding'=>['code'=>$value]]);

                            break;

                        case "code":
                            if (strpos($value,"|")>0)
                            {
                                $explodeValue= explode('|',$value);
                                $procedures->whereJsonContains('code',['coding'=>['system'=>$explodeValue[0]]]);
                                $procedures->whereJsonContains('code',['coding'=>['code'=>$explodeValue[1]]]);
                            }
                            else
                                $procedures->whereJsonContains('code',['coding'=>['code'=>$value]]);
                            break;

                        case "patient":
                            if (strpos($value,"/")>0)
                            {
                                $explodeValue= explode('/',$value);
                                $this->mapperToEloquent($procedures,$mapper[$key],$explodeValue[1]);
                            }
                            else
                                $this->mapperToEloquent($procedures,$mapper[$key],"Patient/".$value);
                        break;

                        case "date":

                            $evaluator= "=";
                            switch (substr($value,0,2))
                            {
                                case "gt":
                                    $evaluator= ">";
                                    break;
                            }

                            $procedures->where('effectiveDateTime',$evaluator,date('Y-m-d H:i:s',strtotime(substr($value,2))));
                            break;

                        default:
                            $this->mapperToEloquent($procedures,$mapper[$key],$value);
                            break;
                    }
                }
            }
        }

        if (!$request->wantsJson())
            return response()->xml($procedures->limit(100)->get());
        else
        {
            $finalResponse= [];
            foreach ($procedures->limit(100)->get() as $pat)
            {
                $response= $this->fhirStructure($pat);
                $finalResponse[]= $response;
            }

            return $finalResponse;
        }


    }

    public function show($procedureID)
    {
        $procedure= Procedure::orWhere(['id'=>$procedureID])->orWhereJsonContains('identifier',['value'=>$procedureID])->first();
//        $documentreferences->whereJsonContains('code',['coding'=>['code'=>$explodeValue[1]]]);

        if ($procedure==null)
            throw new NotFoundResourceException();

        $response= $this->fhirStructure($procedure);
        $finalResponse= ["resource"=>$response];

        return $finalResponse;
    }

}
