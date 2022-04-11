<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\MedicationRequest;

class MedicationRequestController extends Controller
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

    private function fhirStructure($medicationrequest)
    {
        if ($medicationrequest!=[])
        {
            $response= [
                'resourceType'=>"MedicationRequest",
                //'id'=>strval($medicationrequest->id),
                'id'=>strval($medicationrequest->id),
                "text"=> [
                    "status"=> "generated",
                    "div"=> "<div xmlns=\"http://www.w3.org/1999/xhtml\">Success!</div>"
                ],
                'identifier' => $medicationrequest->identifier,
                'status' => $medicationrequest->status,
                'statusReason' => [$medicationrequest->statusReason],
                'intent' => $medicationrequest->intent,
                'category' => [$medicationrequest->category],
                'priority' => $medicationrequest->priority,
                'doNotPerform' => $medicationrequest->doNotPerform,
                'reportedBoolean' => $medicationrequest->reportedBoolean,
                'reportedReference' => [$medicationrequest->reportedReference],
                'medicationCodeableConcept' => [$medicationrequest->medicationCodeableConcept],
                'medicationReference' => $medicationrequest->medicationReference,
                'subject' => $medicationrequest->subject,
                'encounter' => [$medicationrequest->encounter],
                'supportingInformation' => [$medicationrequest->supportingInformation],
                'authoredOn' => $medicationrequest->authoredOn,
                'requester' => $medicationrequest->requester,
                'performer' => [$medicationrequest->performer],
                'performerType' => [$medicationrequest->performerType],
                'recorder' => [$medicationrequest->recorder],
                'reasonCode' => [$medicationrequest->reasonCode],
                'reasonReference' => [$medicationrequest->reasonReference],
                'instantiatesCanonical' => $medicationrequest->instantiatesCanonical,
                'instantiatesUri' => $medicationrequest->instantiatesUri,
                'basedOn' => [$medicationrequest->basedOn],
                'groupIdentifier' => [$medicationrequest->groupIdentifier],
                'courseOfTherapyType' => [$medicationrequest->courseOfTherapyType],
                'note' => [$medicationrequest->note],
                'dosageInstruction' => [$medicationrequest->dosageInstruction],
                'dispenseRequest' => [$medicationrequest->dispenseRequest],
                'substitution' => [$medicationrequest->substitution],
                'priorPrescription' => [$medicationrequest->priorPrescription],
                'detectedIssue' => [$medicationrequest->detectedIssue],
                'eventHistory' => [$medicationrequest->eventHistory],
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
        $medicationrequests= [];
        $medicationrequests= MedicationRequest::query();

        foreach ($request->all() as $key=>$value)
        {
            $key= strtolower($key);
            // Methods with underscore
            if (substr($key,0,1)=="_")
            {
                $medicationrequests->where($mapperUnderscore[substr($key,1)],'=',$value);
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
                            $medicationrequests->where('identifier->system','=',$explodeValue[0]);
                            $medicationrequests->where('identifier->value','=',$explodeValue[1]);
                        }
                        else
                            $this->mapperToEloquent($medicationrequests,$mapper[$key],$value);

                    }
                    else
                    {
                        if ($key=="patient")
                        {
                            if (strpos($value,"/")>0)
                            {
                                die("ere");
                                $explodeValue= explode('/',$value);
                                $this->mapperToEloquent($medicationrequests,$mapper[$key],$explodeValue[1]);
                            }
                            else
                                $this->mapperToEloquent($medicationrequests,$mapper[$key],"Patient/".$value);
                        }
                        else
                            $this->mapperToEloquent($medicationrequests,$mapper[$key],$value);

                    }
                }
            }
        }

        if (!$request->wantsJson())
            return response()->xml($medicationrequests->limit(100)->get());
        else
        {
            $finalResponse= [];
            foreach ($medicationrequests->limit(100)->get() as $pat)
            {
                $response= $this->fhirStructure($pat);
                $finalResponse[]= $response;
            }

            return $finalResponse;
        }


    }

    public function show($medicationrequestID)
    {
        $medicationrequest= MedicationRequest::orWhere(['id'=>$medicationrequestID])->orWhereJsonContains('identifier',['value'=>$medicationrequestID])->first();
//        $documentreferences->whereJsonContains('code',['coding'=>['code'=>$explodeValue[1]]]);

        if ($medicationrequest==null)
            throw new NotFoundResourceException();

        $response= $this->fhirStructure($medicationrequest);
        $finalResponse= ["resource"=>$response];

        return $finalResponse;
    }
}
