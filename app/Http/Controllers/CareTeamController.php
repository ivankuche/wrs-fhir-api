<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\CareTeam;

class CareTeamController extends Controller
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

    private function fhirStructure($careteam)
    {
        if ($careteam!=[])
        {
            $response= [
                'resourceType'=>"CareTeam",
                'id'=>strval($careteam->id),
                "text"=> [
                    "status"=> "generated",
                    "div"=> "<div xmlns=\"http://www.w3.org/1999/xhtml\">Success!</div>"
                ],
                'identifier' => [$careteam->identifier],
                'status' => $careteam->status,
                'category' => [$careteam->category],
                'name' => $careteam->name,
                'subject' => $careteam->subject,
                'encounter' => [$careteam->encounter],
                'period' => [$careteam->period],
                'participant' => [$careteam->participant],
                'reasonCode' => [$careteam->reasonCode],
                'reasonReference' => [$careteam->reasonReference],
                'managingOrganization' => [$careteam->managingOrganization],
                'telecom' => [$careteam->telecom],
                'note' => [$careteam->note],
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
            "status"=>["status"]

        ];
        $mapperUnderscore= [
            "id"=>"id",
        ];
        $conditions= [];
        $careteams= CareTeam::query();

        foreach ($request->all() as $key=>$value)
        {
            $key= strtolower($key);
            // Methods with underscore
            if (substr($key,0,1)=="_")
            {
                $careteams->where($mapperUnderscore[substr($key,1)],'=',$value);
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
                            $careteams->where('identifier->system','=',$explodeValue[0]);
                            $careteams->where('identifier->value','=',$explodeValue[1]);
                        }
                        else
                            $this->mapperToEloquent($careteams,$mapper[$key],$value);

                    }
                    else
                        $this->mapperToEloquent($careteams,$mapper[$key],$value);
                }
            }
        }

        if (!$request->wantsJson())
            return response()->xml($careteams->limit(100)->get());
        else
        {
            $finalResponse= [];
            foreach ($careteams->limit(100)->get() as $pat)
            {
                $response= $this->fhirStructure($pat);
                $finalResponse[]= $response;
            }

            return $finalResponse;
        }


    }

    public function show($allergyIntoleranceID)
    {
        $careteam= CareTeam::findOrFail($allergyIntoleranceID);
        $response= $this->fhirStructure($careteam);
        $finalResponse= ["resource"=>$response];

        return $finalResponse;
    }
}
