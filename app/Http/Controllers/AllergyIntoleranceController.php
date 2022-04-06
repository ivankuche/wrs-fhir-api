<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\AllergyIntolerance;

class AllergyIntoleranceController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */


    private function filterEmpty(&$array)
    {
        foreach ( $array as $key => $item ) {
            is_array ( $item ) && $array [$key] = $this->filterEmpty( $item );
            if (empty ( $array [$key] ))
                unset ( $array [$key] );
        }
        return $array;
    }

    private function fhirStructure($allergyintolerance)
    {
        if ($allergyintolerance!=[])
        {
            $response= [
                'resourceType'=>"AllergyIntolerance",
                'id'=>strval($allergyintolerance->id),
                'identifier' => [$allergyintolerance->identifier],
                'clinicalStatus' => [$allergyintolerance->clinicalStatus],
                'verificationStatus' => [$allergyintolerance->verificationStatus],
                'type' => $allergyintolerance->type,
                'category' => $allergyintolerance->category,
                'criticality' => $allergyintolerance->criticality,
                'code' => [$allergyintolerance->code],
                'patient' => [$allergyintolerance->patient],
                'encounter' => [$allergyintolerance->encounter],
                'onsetDateTime' => $allergyintolerance->onsetDateTime,
                'onsetAge' => $allergyintolerance->onsetAge,
                'onsetPeriod' => [$allergyintolerance->onsetPeriod],
                'onsetRange' => [$allergyintolerance->onsetRange],
                'onsetString' => $allergyintolerance->onsetString,
                'recordedDate' => $allergyintolerance->recordedDate,
                'recorder' => [$allergyintolerance->recorder],
                'asserter' => [$allergyintolerance->asserter],
                'lastOccurrence' => $allergyintolerance->lastOccurrence,
                'note' => [$allergyintolerance->note],
                'reaction' => [$allergyintolerance->reaction],
            ];

            $response= $this->filterEmpty($response);
        }
        else
            $response=[];

        return $response;
    }

    private function renameParams($params)
    {
        $mapper= ["_id"=>"id"];
        $mapperUnderscore= ["id"=>"id"];
        $output= [];

        foreach ($params as $key=>$value)
        {
            if (substr($key,0,1)=="_")
            {
                $output[$mapperUnderscore[substr($key,1)]]= $value;
            }
            else
            {

                if (in_array($key,$mapper))
                    $output[$mapper[$key]]= $value;
                else
                    $output[$key]= $value;
            }
        }

        return $output;
    }

    private function validParameters($params)
    {
        $valid= ['birthdate','name->text','gender','identifier','_id','_revinclude'];
        $invalid= [];

        foreach ($params as $key=>$value)
        {
            if (!in_array($key,$valid))
                $invalid[]= $key;
        }

        if (count($invalid)>0)
            throw new Exception("Invalid fields: ".implode(", ",$invalid),500);

        return $this->renameParams($params);
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
            "patient"=>["patient->reference"],

        ];
        $mapperUnderscore= [
            "id"=>"id",
        ];
        $conditions= [];
        $patients= AllergyIntolerance::query();

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
                            $this->mapperToEloquent($patients,$mapper[$key],strtolower($value));
                            //$patients->where($mapper[$key],'=',$value);

                    }
                    else
                        $this->mapperToEloquent($patients,$mapper[$key],strtolower($value));
                        //$patients->where($mapper[$key],'=',$value);
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

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\AllergyIntolerance  $patient
     * @return \Illuminate\Http\Response
     */
    public function show($allergyIntoleranceID)
    {
        $allergyintolerance= AllergyIntolerance::findOrFail($allergyIntoleranceID);
        $response= $this->fhirStructure($allergyintolerance);
        $finalResponse= ["resource"=>$response];

        return $finalResponse;
    }

}
