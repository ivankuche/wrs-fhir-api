<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\DiagnosticReport;
use Carbon\Carbon;


class DiagnosticReportController extends Controller
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

    private function fhirStructure($diagnosticreport)
    {
        if ($diagnosticreport!=[])
        {
            $response= [
                'resourceType'=>"DiagnosticReport",
                'id'=>strval($diagnosticreport->id),
                "text"=> [
                    "status"=> "generated",
                    "div"=> "<div xmlns=\"http://www.w3.org/1999/xhtml\">Success!</div>"
                ],
                // Profile of the extension
                "meta" => [
                    "profile" => [
                        "http://hl7.org/fhir/us/core/StructureDefinition/us-core-diagnosticreport-note",
                        "http://hl7.org/fhir/us/core/StructureDefinition/us-core-diagnosticreport-lab"
                      ]
                ],
                'identifier' => [$diagnosticreport->identifier],
                'basedOn' => $diagnosticreport->basedOn,
                'status' => $diagnosticreport->status,
                'category' => [$diagnosticreport->category],
                'code' => $diagnosticreport->code,
                'subject' => $diagnosticreport->subject,
                'encounter' => $diagnosticreport->encounter,
                'effectiveDateTime' => Carbon::parse($diagnosticreport->effectiveDateTime)->toIso8601String(),
                'effectivePeriod' => [$diagnosticreport->effectivePeriod],
                'issued' => Carbon::parse($diagnosticreport->issued)->toIso8601String(),
                'performer' => $diagnosticreport->performer,
                'resultsInterpreter' => $diagnosticreport->resultsInterpreter,
                'specimen' => $diagnosticreport->specimen,
                'result' => $diagnosticreport->result,
                'note' => [$diagnosticreport->note],
                'imagingStudy' => $diagnosticreport->imagingStudy,
                'media' => [$diagnosticreport->media],
                'composition' => $diagnosticreport->composition,
                'conclusion' => $diagnosticreport->conclusion,
                'conclusionCode' => [$diagnosticreport->conclusionCode],
                'presentedForm' => [$diagnosticreport->presentedForm],
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
            "category"=>["category->coding->code"],
            "code"=>["code->coding->code"],
            //"status"=>["status"]

        ];
        $mapperUnderscore= [
            "id"=>"id",
        ];
        $diagnosticreports= [];
        $diagnosticreports= DiagnosticReport::query();

        foreach ($request->all() as $key=>$value)
        {
            $key= strtolower($key);
            // Methods with underscore
            if (substr($key,0,1)=="_")
            {
                $diagnosticreports->where($mapperUnderscore[substr($key,1)],'=',$value);
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
                                $diagnosticreports->where('identifier->system','=',$explodeValue[0]);
                                $diagnosticreports->where('identifier->value','=',$explodeValue[1]);
                            }
                            else
                                $this->mapperToEloquent($diagnosticreports,$mapper[$key],$value);
                            break;

                        case "category":
                            if (strpos($value,"|")>0)
                            {
                                $explodeValue= explode('|',$value);
                                $diagnosticreports->where('category->coding->system','=',$explodeValue[0]);
                                $diagnosticreports->where('category->coding->code','=',$explodeValue[1]);
                            }
                            else
                                $this->mapperToEloquent($diagnosticreports,$mapper[$key],$value);

                            break;

                        case "code":
                            $diagnosticreports->whereJsonContains('code',['coding'=>['code'=>$value]]);
                            break;

                        default:
                            $this->mapperToEloquent($diagnosticreports,$mapper[$key],$value);
                            break;
                    }
                }
            }
        }

        if (!$request->wantsJson())
            return response()->xml($diagnosticreports->limit(100)->get());
        else
        {
            // Your Eloquent query executed by using get()

            $finalResponse= [];
            foreach ($diagnosticreports->limit(100)->get() as $pat)
            {
                $response= $this->fhirStructure($pat);
                $finalResponse[]= $response;
            }

            return $finalResponse;
        }


    }

    public function show($allergyIntoleranceID)
    {
        $diagnosticreport= DiagnosticReport::findOrFail($allergyIntoleranceID);
        $response= $this->fhirStructure($diagnosticreport);
        $finalResponse= ["resource"=>$response];

        return $finalResponse;
    }

}
