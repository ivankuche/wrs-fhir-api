<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Immunization;
use Carbon\Carbon;

class ImmunizationController extends Controller
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

    private function fhirStructure($immunization)
    {
        if ($immunization!=[])
        {
            $response= [
                'resourceType'=>"Immunization",
                'id'=>strval($immunization->id),
                "text"=> [
                    "status"=> "generated",
                    "div"=> "<div xmlns=\"http://www.w3.org/1999/xhtml\">Success!</div>"
                ],
                'identifier' => [$immunization->identifier],
                'instantiatesCanonical' => [$immunization->instantiatesCanonical],
                'instantiatesUri' => $immunization->instantiatesUri,
                'basedOn' => [$immunization->basedOn],
                'status' => $immunization->status,
                'statusReason' => [$immunization->statusReason],
                'vaccineCode' => $immunization->vaccineCode,
                'manufacturer' => [$immunization->manufacturer],
                'lotNumber' => $immunization->lotNumber,
                'expirationDate' => $immunization->expirationDate,
                'patient' => $immunization->patient,
                'encounter' => [$immunization->encounter],
                'occurrenceDateTime' => Carbon::parse($immunization->occurrenceDateTime)->toIso8601String(),
                'occurrenceString' => $immunization->occurrenceString,
                'recorded' => $immunization->recorded,
                'primarySource' => ($immunization->primarySource?true:false),
                'informationSource' => [$immunization->informationSource],
                'location' => [$immunization->location],
                'site' => [$immunization->site],
                'route' => [$immunization->route],
                'doseQuantity' => [$immunization->doseQuantity],
                'performer' => [$immunization->performer],
                'note' => [$immunization->note],
                'reason' => [$immunization->reason],
                'isSubpotent' => $immunization->isSubpotent,
                'subpotentReason' => [$immunization->subpotentReason],
                'education' => [$immunization->education],
                'programEligibility' => [$immunization->programEligibility],
                'fundingSource' => [$immunization->fundingSource],
                'reaction' => [$immunization->reaction],
                'protocolApplied' => [$immunization->protocolApplied],
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
            "patient"=>["patient->reference"],
            //"status"=>["status"]

        ];
        $mapperUnderscore= [
            "id"=>"id",
        ];
        $immunizations= [];
        $immunizations= Immunization::query();

        foreach ($request->all() as $key=>$value)
        {
            $key= strtolower($key);
            // Methods with underscore
            if (substr($key,0,1)=="_")
            {
                $immunizations->where($mapperUnderscore[substr($key,1)],'=',$value);
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
                            $immunizations->where('identifier->system','=',$explodeValue[0]);
                            $immunizations->where('identifier->value','=',$explodeValue[1]);
                        }
                        else
                            $this->mapperToEloquent($immunizations,$mapper[$key],$value);

                    }
                    else
                    {
                        if ($key=="patient")
                        {
                            if (strpos($value,"/")>0)
                            {
                                $explodeValue= explode('/',$value);
                                $this->mapperToEloquent($immunizations,$mapper[$key],$explodeValue[1]);
                            }
                            else
                                $this->mapperToEloquent($immunizations,$mapper[$key],"Patient/".$value);
                        }
                        else
                            $this->mapperToEloquent($immunizations,$mapper[$key],$value);
                    }
                }
            }
        }

        if (!$request->wantsJson())
            return response()->xml($immunizations->limit(100)->get());
        else
        {
            $finalResponse= [];
            foreach ($immunizations->limit(100)->get() as $pat)
            {
                $response= $this->fhirStructure($pat);
                $finalResponse[]= $response;
            }

            return $finalResponse;
        }


    }

    public function show($immunizationID)
    {
        $immunization= Immunization::findOrFail($immunizationID);
        $response= $this->fhirStructure($immunization);
        $finalResponse= ["resource"=>$response];

        return $finalResponse;
    }

}
