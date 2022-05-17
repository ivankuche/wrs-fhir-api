<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Group;

class GroupController extends Controller
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

    private function fhirStructure($group)
    {
        if ($group!=[])
        {
            $response= [
                'resourceType'=>"Group",
                'id'=>strval($group->id),
                "text"=> [
                    "status"=> "generated",
                    "div"=> "<div xmlns=\"http://www.w3.org/1999/xhtml\">Success!</div>"
                ],
                'identifier' => [$group->identifier],
                'active' => ($group->active?true:false),
                'type' => $group->type,
                'actual' => ($group->actual?true:false),
                'code' => $group->code,
                'name' => $group->name,
                'description' => $group->description,
                'quantity' => $group->quantity,
                'managingEntity' => $group->managingEntity,
                'characteristic' => [$group->characteristic],
                'member' => [$group->member],
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
            //"patient"=>["subject->reference"],
            //"status"=>["status"]

        ];
        $mapperUnderscore= [
            "id"=>"id",
        ];
        $groups= [];
        $groups= Group::query();

        foreach ($request->all() as $key=>$value)
        {
            $key= strtolower($key);
            // Methods with underscore
            if (substr($key,0,1)=="_")
            {
                $groups->where($mapperUnderscore[substr($key,1)],'=',$value);
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
                            $groups->where('identifier->system','=',$explodeValue[0]);
                            $groups->where('identifier->value','=',$explodeValue[1]);
                        }
                        else
                            $this->mapperToEloquent($groups,$mapper[$key],$value);

                    }
                    else
                    {
                        if ($key=="patient")
                        {
                            if (strpos($value,"/")>0)
                            {
                                $explodeValue= explode('/',$value);
                                $this->mapperToEloquent($groups,$mapper[$key],$explodeValue[1]);
                            }
                            else
                                $this->mapperToEloquent($groups,$mapper[$key],"Patient/".$value);
                        }
                        else
                            $this->mapperToEloquent($groups,$mapper[$key],$value);
                    }
                }
            }
        }

        if (!$request->wantsJson())
            return response()->xml($groups->limit(100)->get());
        else
        {
            $finalResponse= [];
            foreach ($groups->limit(100)->get() as $pat)
            {
                $response= $this->fhirStructure($pat);
                $finalResponse[]= $response;
            }

            return $finalResponse;
        }


    }

    public function show($groupID)
    {
        $group= Group::findOrFail($groupID);
        $response= $this->fhirStructure($group);
        $finalResponse= ["resource"=>$response];

        return $finalResponse;
    }

}
