<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\CarePlan;

class CarePlanController extends Controller
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

    private function fhirStructure($careplan)
    {
        if ($careplan!=[])
        {
            $response= [
                'resourceType'=>"CarePlan",
                'id'=>strval($careplan->id),
                "text"=> [
                    "status"=> "generated",
                    "div"=> "<div xmlns=\"http://www.w3.org/1999/xhtml\">Success!</div>"
                ],
                'identifier' => [$careplan->identifier],
                'instantiatesCanonical' => [$careplan->instantiatesCanonical],
                'instantiatesUri' => [$careplan->instantiatesUri],
                'basedOn' => [$careplan->basedOn],
                'replaces' => [$careplan->replaces],
                'partOf' => [$careplan->partOf],
                'status' => $careplan->status,
                'intent' => $careplan->intent,
                'category' => [$careplan->category],
                'title' => $careplan->title,
                'description' => [$careplan->description],
                'subject' => $careplan->subject,
                'encounter' => [$careplan->encounter],
                'period' => [$careplan->period],
                'created' => $careplan->created,
                'author' => [$careplan->author],
                'contributor' => [$careplan->contributor],
                'careTeam' => [$careplan->careTeam],
                'addresses' => [$careplan->addresses],
                'supportingInfo' => [$careplan->supportingInfo],
                'goal' => [$careplan->goal],
                'activity' => [$careplan->activity],
                'note' => [$careplan->note],
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
                $element->orWhere($option,'=',$value);
        });
    }

    public function index(Request $request)
    {

        $mapper= [
            "patient"=>["subject->reference"],

        ];
        $mapperUnderscore= [
            "id"=>"id",
        ];
        $conditions= [];
        $patients= CarePlan::query();

        foreach ($request->all() as $key=>$value)
        {
            $key= strtolower($key);
            // Methods with underscore
            if (substr($key,0,1)=="_")
            {
                $patients->where($mapperUnderscore[substr($key,1)],'=',$value);
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
                            $patients->where('identifier->system','=',$explodeValue[0]);
                            $patients->where('identifier->value','=',$explodeValue[1]);
                        }
                        else
                            $this->mapperToEloquent($patients,$mapper[$key],$value);
                    }

                    else
                    {
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
            return response()->xml($patients->limit(100)->get());
        else
        {
            $finalResponse= [];
            //DB::enableQueryLog();
            foreach ($patients->limit(100)->get() as $pat)
            {
                $response= $this->fhirStructure($pat);
                $finalResponse[]= $response;
            }

            //print_r(DB::getQueryLog());
            ///die();

            return $finalResponse;
            //return $patients->limit(100)->get();
        }


    }

    public function show($allergyIntoleranceID)
    {
        $careplan= CarePlan::findOrFail($allergyIntoleranceID);
        $response= $this->fhirStructure($careplan);
        $finalResponse= ["resource"=>$response];

        return $finalResponse;
    }


}
