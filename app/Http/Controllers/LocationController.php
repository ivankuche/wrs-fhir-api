<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Location;

class LocationController extends Controller
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

    private function fhirStructure($location)
    {
        if ($location!=[])
        {
            $response= [
                'resourceType'=>"Location",
                //'id'=>strval($location->id),
                'id'=>strval($location->id),
                "text"=> [
                    "status"=> "generated",
                    "div"=> "<div xmlns=\"http://www.w3.org/1999/xhtml\">Success!</div>"
                ],
                'identifier' => $location->identifier,
                'status' => $location->status,
                'operationalStatus' => $location->operationalStatus,
                'name' => $location->name,
                'alias' => $location->alias,
                'description' => $location->description,
                'mode'  => $location->mode,
                'type'  => $location->type,
                'telecom'  => $location->telecom,
                'address'  => $location->address,
                'physicalType'  => $location->physicalType,
                'position'  => $location->position,
                'managingOrganization'  => $location->managingOrganization,
                'partOf'  => $location->partOf,
                'hoursOfOperation'  => $location->hoursOfOperation,
                'availabilityExceptions'  => $location->availabilityExceptions,
                'endpoint'  => $location->endpoint,
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
        $locations= [];
        $locations= Location::query();

        foreach ($request->all() as $key=>$value)
        {
            $key= strtolower($key);
            // Methods with underscore
            if (substr($key,0,1)=="_")
            {
                $locations->where($mapperUnderscore[substr($key,1)],'=',$value);
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
                                $locations->where('identifier->system','=',$explodeValue[0]);
                                $locations->where('identifier->value','=',$explodeValue[1]);
                            }
                            else
                                $this->mapperToEloquent($locations,$mapper[$key],$value);
                            break;

                        case "category":
                            if (strpos($value,"|")>0)
                            {
                                $explodeValue= explode('|',$value);
                                $locations->whereJsonContains('category',['coding'=>['system'=>$explodeValue[0]]]);
                                $locations->whereJsonContains('category',['coding'=>['code'=>$explodeValue[1]]]);
                            }
                            else
                            $locations->whereJsonContains('category',['coding'=>['code'=>$value]]);

                            break;

                        case "code":
                            if (strpos($value,"|")>0)
                            {
                                $explodeValue= explode('|',$value);
                                $locations->whereJsonContains('code',['coding'=>['system'=>$explodeValue[0]]]);
                                $locations->whereJsonContains('code',['coding'=>['code'=>$explodeValue[1]]]);
                            }
                            else
                                $locations->whereJsonContains('code',['coding'=>['code'=>$value]]);
                            break;

                        case "patient":
                            if (strpos($value,"/")>0)
                            {
                                $explodeValue= explode('/',$value);
                                $this->mapperToEloquent($locations,$mapper[$key],$explodeValue[1]);
                            }
                            else
                                $this->mapperToEloquent($locations,$mapper[$key],"Patient/".$value);
                        break;

                        case "date":

                            $evaluator= "=";
                            switch (substr($value,0,2))
                            {
                                case "gt":
                                    $evaluator= ">";
                                    break;
                            }

                            $locations->where('effectiveDateTime',$evaluator,date('Y-m-d H:i:s',strtotime(substr($value,2))));
                            break;

                        default:
                            $this->mapperToEloquent($locations,$mapper[$key],$value);
                            break;
                    }
                }
            }
        }

        if (!$request->wantsJson())
            return response()->xml($locations->limit(100)->get());
        else
        {
            $finalResponse= [];
            foreach ($locations->limit(100)->get() as $pat)
            {
                $response= $this->fhirStructure($pat);
                $finalResponse[]= $response;
            }

            return $finalResponse;
        }


    }

    public function show($locationID)
    {
        $location= Location::orWhere(['id'=>$locationID])->orWhereJsonContains('identifier',['value'=>$locationID])->first();
//        $documentreferences->whereJsonContains('code',['coding'=>['code'=>$explodeValue[1]]]);

        if ($location==null)
            throw new NotFoundResourceException();

        $response= $this->fhirStructure($location);
        $finalResponse= ["resource"=>$response];

        return $finalResponse;
    }


}
