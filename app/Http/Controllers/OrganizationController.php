<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Organization;
use Symfony\Component\Translation\Exception\NotFoundResourceException;

class OrganizationController extends Controller
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

    private function fhirStructure($organization)
    {
        if ($organization!=[])
        {
            $response= [
                'resourceType'=>"Organization",
                'id'=>strval($organization->id),
                "text"=> [
                    "status"=> "generated",
                    "div"=> "<div xmlns=\"http://www.w3.org/1999/xhtml\">Success!</div>"
                ],
                'identifier' => [$organization->identifier],
                'active' => $organization->active,
                'type' => [$organization->type],
                'name' => $organization->name,
                'alias' => $organization->alias,
                'telecom' => [$organization->telecom],
                'address' => [$organization->address],
                'partOf' => [$organization->partOf],
                'contact' => [$organization->contact],
                'endpoint' => [$organization->endpoint],
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

        $mapper= [];
        $mapperUnderscore= [
            "id"=>"id",
        ];
        $organizations= [];
        $organizations= Organization::query();

        foreach ($request->all() as $key=>$value)
        {
            $key= strtolower($key);
            // Methods with underscore
            if (substr($key,0,1)=="_")
            {
                $organizations->where($mapperUnderscore[substr($key,1)],'=',$value);
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
                            $organizations->where('identifier->system','=',$explodeValue[0]);
                            $organizations->where('identifier->value','=',$explodeValue[1]);
                        }
                        else
                            $this->mapperToEloquent($organizations,$mapper[$key],$value);

                    }
                    else
                        $this->mapperToEloquent($organizations,$mapper[$key],$value);
                }
            }
        }

        if (!$request->wantsJson())
            return response()->xml($organizations->limit(100)->get());
        else
        {
            $finalResponse= [];
            foreach ($organizations->limit(100)->get() as $pat)
            {
                $response= $this->fhirStructure($pat);
                $finalResponse[]= $response;
            }

            return $finalResponse;
        }


    }

    public function show($organizationID)
    {
        $organization= Organization::orWhere(['id'=>$organizationID])->orWhere(['name'=>$organizationID])->first();
        if (count($organization->all())==0)
            throw new NotFoundResourceException();

        $response= $this->fhirStructure($organization);
        $finalResponse= ["resource"=>$response];

        return $finalResponse;
    }

}
