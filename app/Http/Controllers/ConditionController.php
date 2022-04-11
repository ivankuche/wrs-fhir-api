<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Condition;

class ConditionController extends Controller
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

    private function fhirStructure($condition)
    {
        if ($condition!=[])
        {
            $response= [
                'resourceType'=>"Condition",
                'id'=>strval($condition->id),
                "text"=> [
                    "status"=> "generated",
                    "div"=> "<div xmlns=\"http://www.w3.org/1999/xhtml\">Success!</div>"
                ],
                'identifier' => [$condition->identifier],
                'clinicalStatus' => $condition->clinicalStatus,
                'verificationStatus' => $condition->verificationStatus,
                'category' => [$condition->category],
                'severity' => [$condition->severity],
                'code' => $condition->code,
                'bodySite' => [$condition->bodySite],
                'subject' => $condition->subject,
                'encounter' => [$condition->encounter],
                'onsetDateTime' => $condition->onsetDateTime,
                'onsetAge' => $condition->onsetAge,
                'onsetPeriod' => [$condition->onsetPeriod],
                'onsetRange' => [$condition->onsetRange],
                'onsetString' => $condition->onsetString,
                'abatementDateTime' => $condition->abatementDateTime,
                'abatementAge' => $condition->abatementAge,
                'abatementPeriod' => [$condition->abatementPeriod],
                'abatementRange' => [$condition->abatementRange],
                'abatementString' => $condition->abatementString,
                'recordedDate' => $condition->recordedDate,
                'recorder' => [$condition->recorder],
                'asserter' => [$condition->asserter],
                'stage' => [$condition->stage],
                'evidence' => [$condition->evidence],
                'note' => [$condition->note],
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
        $conditions= [];
        $conditions= Condition::query();

        foreach ($request->all() as $key=>$value)
        {
            $key= strtolower($key);
            // Methods with underscore
            if (substr($key,0,1)=="_")
            {
                $conditions->where($mapperUnderscore[substr($key,1)],'=',$value);
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
                            $conditions->where('identifier->system','=',$explodeValue[0]);
                            $conditions->where('identifier->value','=',$explodeValue[1]);
                        }
                        else
                            $this->mapperToEloquent($conditions,$mapper[$key],$value);

                    }
                    else{
                        if ($key=="patient")
                        {
                            $newValue= "";
                            if (strpos($value,"Patient/")>0)
                                $newValue= $value;
                            else
                                $newValue= "Patient/".$value;

                            $this->mapperToEloquent($patients,$mapper[$key],$newValue);


                        }
                        else
                            $this->mapperToEloquent($patients,$mapper[$key],$value);

                    }
                }
            }
        }

        if (!$request->wantsJson())
            return response()->xml($conditions->limit(100)->get());
        else
        {
            $finalResponse= [];
            foreach ($conditions->limit(100)->get() as $pat)
            {
                $response= $this->fhirStructure($pat);
                $finalResponse[]= $response;
            }

            return $finalResponse;
        }


    }

    public function show($allergyIntoleranceID)
    {
        $condition= Condition::findOrFail($allergyIntoleranceID);
        $response= $this->fhirStructure($condition);
        $finalResponse= ["resource"=>$response];

        return $finalResponse;
    }
}
