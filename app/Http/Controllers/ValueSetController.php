<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use GuzzleHttp\Client;
use Symfony\Component\CssSelector\Exception\InternalErrorException;


class ValueSetController extends Controller
{

    private $sources= [
        "FHIRValueSet",
//        "SnomedCT",
    ];

    private function getMethods()
    {
        $methods= [];
        foreach ($this->sources as $sourceMethod)
        {
            $urlMethod= url($sourceMethod."/methods");
            $clientMethod = new Client();
            $responseMethod = $clientMethod->get($urlMethod,['verify' => false, 'headers'=>['Connection' => 'close']]); // Verify false to avoid auto SSL errors
            if ($responseMethod->getStatusCode()==200)
            {
                $bodyMethod= json_decode($responseMethod->getBody());

                foreach ($bodyMethod as $elementMethod)
                    $methods[$elementMethod][]= $sourceMethod;
            }
            else
                throw new InternalErrorException($responseMethod->getStatusCode());

        }

        return $methods;

    }

    public function getValueSet($ValueSet,$Term=null,$Sort=null)
    {

        $methods= $this->getMethods();
        /*
        $methods=  [
            "AddressType" => [
                "FHIRValueSet"
            ]
        ];
        */

        if (array_key_exists($ValueSet,$methods))
        {
            $data= [];
            $meta= [];


            foreach ($methods[$ValueSet] as $method)
            {
                $urlValueSet= url($method."/".$ValueSet."/".$Term);
                $clientValueSet = new Client();
                $responseValueSet = $clientValueSet->get($urlValueSet,['verify' => false,'headers'=>['Connection' => 'close']]); // Verify false to avoid auto SSL errors

                if ($responseValueSet->getStatusCode()==200)
                {
                    $body= json_decode($responseValueSet->getBody());
                    if ($body->data!=[])
                    {
                        $data[]= $body->data;
                        $meta[]= $body->meta;
                    }
                }
                else
                    throw new InternalErrorException($responseValueSet->getStatusCode());
            }

            if ($data!=[])
                if ($Sort!=null)
                    $data= collect($data)->sortBy($Sort,SORT_STRING)->values();

            return [
                "meta"=>$meta,
                "data"=>$data
            ];
        }

        throw new NotFoundHttpException("ValueSet not found");
    }
}
