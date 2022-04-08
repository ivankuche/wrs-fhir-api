<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Device;
use Carbon\Carbon;

class DeviceController extends Controller
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

    private function fhirStructure($device)
    {
        if ($device!=[])
        {
            $response= [
                'resourceType'=>"Device",
                'id'=>strval($device->id),
                "text"=> [
                    "status"=> "generated",
                    "div"=> "<div xmlns=\"http://www.w3.org/1999/xhtml\">Success!</div>"
                ],
                // Profile of the extension
                "meta" => [
                    "profile" => [
                        "http://hl7.org/fhir/us/core/StructureDefinition/us-core-implantable-device"
                      ]
                ],
                'identifier' => [$device->identifier],
                'udi' => [$device->udi],
                'status' => $device->status,
                'type' => $device->type,
                'lotNumber' => $device->lotNumber,
                'manufacturer' => $device->manufacturer,
                'manufactureDate' => Carbon::parse($device->manufactureDate)->toIso8601String(),
                'expirationDate' => Carbon::parse($device->expirationDate)->toIso8601String(),
                'model' => $device->model,
                'version' => $device->version,
                'patient' => $device->patient,
                'owner' => [$device->owner],
                'contact' => [$device->contact],
                'location' => [$device->location],
                'url' => $device->url,
                'note' => [$device->note],
                'safety' => [$device->safety],
                'distinctIdentifier' => $device->distinctIdentifier,
                'serialNumber' => $device->serialNumber,
                'udiCarrier' => [$device->udiCarrier],
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
        $devices= [];
        $devices= Device::query();

        foreach ($request->all() as $key=>$value)
        {
            $key= strtolower($key);
            // Methods with underscore
            if (substr($key,0,1)=="_")
            {
                $devices->where($mapperUnderscore[substr($key,1)],'=',$value);
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
                            $devices->where('identifier->system','=',$explodeValue[0]);
                            $devices->where('identifier->value','=',$explodeValue[1]);
                        }
                        else
                            $this->mapperToEloquent($devices,$mapper[$key],$value);

                    }
                    else
                        $this->mapperToEloquent($devices,$mapper[$key],$value);
                }
            }
        }

        if (!$request->wantsJson())
            return response()->xml($devices->limit(100)->get());
        else
        {
            $finalResponse= [];
            foreach ($devices->limit(100)->get() as $pat)
            {
                $response= $this->fhirStructure($pat);
                $finalResponse[]= $response;
            }

            return $finalResponse;
        }


    }

    public function show($allergyIntoleranceID)
    {
        $device= Device::findOrFail($allergyIntoleranceID);
        $response= $this->fhirStructure($device);
        $finalResponse= ["resource"=>$response];

        return $finalResponse;
    }

}
