<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Medication;

class MedicationController extends Controller
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

    private function fhirStructure($medication)
    {
        if ($medication!=[])
        {
            $response= [
                'resourceType'=>"Medication",
                //'id'=>strval($medication->id),
                'id'=>strval($medication->id),
                "text"=> [
                    "status"=> "generated",
                    "div"=> "<div xmlns=\"http://www.w3.org/1999/xhtml\">Success!</div>"
                ],
                'identifier' => $medication->identifier,
                'code' => $medication->code,
                'status' => $medication->status,
                'manufacturer' => $medication->manufacturer,
                'form' => $medication->form,
                'amount' => $medication->amount,
                'ingredient' => [$medication->ingredient],
                'batch' => $medication->batch,
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
        $medications= [];
        $medications= Medication::query();

        foreach ($request->all() as $key=>$value)
        {
            $key= strtolower($key);
            // Methods with underscore
            if (substr($key,0,1)=="_")
            {
                $medications->where($mapperUnderscore[substr($key,1)],'=',$value);
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
                            $medications->where('identifier->system','=',$explodeValue[0]);
                            $medications->where('identifier->value','=',$explodeValue[1]);
                        }
                        else
                            $this->mapperToEloquent($medications,$mapper[$key],$value);

                    }
                    else
                    {
                        if ($key=="patient")
                        {
                            if (strpos($value,"/")>0)
                            {
                                die("ere");
                                $explodeValue= explode('/',$value);
                                $this->mapperToEloquent($medications,$mapper[$key],$explodeValue[1]);
                            }
                            else
                                $this->mapperToEloquent($medications,$mapper[$key],"Patient/".$value);
                        }
                        else
                            $this->mapperToEloquent($medications,$mapper[$key],$value);

                    }
                }
            }
        }

        if (!$request->wantsJson())
            return response()->xml($medications->limit(100)->get());
        else
        {
            $finalResponse= [];
            foreach ($medications->limit(100)->get() as $pat)
            {
                $response= $this->fhirStructure($pat);
                $finalResponse[]= $response;
            }

            return $finalResponse;
        }


    }

    public function show($medicationID)
    {
        $medication= Medication::orWhere(['id'=>$medicationID])->orWhereJsonContains('identifier',['value'=>$medicationID])->first();
//        $documentreferences->whereJsonContains('code',['coding'=>['code'=>$explodeValue[1]]]);

        if ($medication==null)
            throw new NotFoundResourceException();

        $response= $this->fhirStructure($medication);
        $finalResponse= ["resource"=>$response];

        return $finalResponse;
    }

}
