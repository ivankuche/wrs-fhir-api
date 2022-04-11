<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Goal;

class GoalController extends Controller
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

    private function fhirStructure($goal)
    {
        if ($goal!=[])
        {
            $response= [
                'resourceType'=>"Goal",
                'id'=>strval($goal->id),
                "text"=> [
                    "status"=> "generated",
                    "div"=> "<div xmlns=\"http://www.w3.org/1999/xhtml\">Success!</div>"
                ],
                'identifier' => [$goal->identifier],
                'lifecycleStatus' => $goal->lifecycleStatus,
                'achievementStatus' => [$goal->achievementStatus],
                'category' => [$goal->category],
                'continuous' => $goal->continuous,
                'priority' => [$goal->priority],
                'description' => $goal->description,
                'subject' => $goal->subject,
                'startDate' => $goal->startDate,
                'startCodeableConcept' => [$goal->startCodeableConcept],
                'target' => [$goal->target],
                'statusDate' => $goal->statusDate,
                'statusReason' => $goal->statusReason,
                'source' => [$goal->source],
                'addresses' => [$goal->addresses],
                'note' => [$goal->note],
                'outcome' => [$goal->outcome],
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
        $goals= [];
        $goals= Goal::query();

        foreach ($request->all() as $key=>$value)
        {
            $key= strtolower($key);
            // Methods with underscore
            if (substr($key,0,1)=="_")
            {
                $goals->where($mapperUnderscore[substr($key,1)],'=',$value);
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
                            $goals->where('identifier->system','=',$explodeValue[0]);
                            $goals->where('identifier->value','=',$explodeValue[1]);
                        }
                        else
                            $this->mapperToEloquent($goals,$mapper[$key],$value);

                    }
                    else
                        $this->mapperToEloquent($goals,$mapper[$key],$value);
                }
            }
        }

        if (!$request->wantsJson())
            return response()->xml($goals->limit(100)->get());
        else
        {
            $finalResponse= [];
            foreach ($goals->limit(100)->get() as $pat)
            {
                $response= $this->fhirStructure($pat);
                $finalResponse[]= $response;
            }

            return $finalResponse;
        }


    }

    public function show($goalID)
    {
        $goal= Goal::findOrFail($goalID);
        $response= $this->fhirStructure($goal);
        $finalResponse= ["resource"=>$response];

        return $finalResponse;
    }

}
