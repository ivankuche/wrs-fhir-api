<?php

namespace App\Http\Controllers;

use Exception;
use Illuminate\Http\Request;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use GuzzleHttp\Client;
use Symfony\Component\CssSelector\Exception\InternalErrorException;

class FHIRValueSetController extends Controller
{
    private $valusets= [
        "IdentifierUse"=>"https://build.fhir.org/codesystem-identifier-use.json",
        "IdentifierType"=>"https://terminology.hl7.org/3.1.0/CodeSystem-v2-0203.json",
        "NameUse"=>"https://hl7.org/fhir/R4/codesystem-name-use.json",
        "ContactPointSystem"=>"https://build.fhir.org/codesystem-contact-point-system.json",
        "ContactPointUse"=>"https://build.fhir.org/codesystem-contact-point-use.json",
        "AdministrativeGender"=>"https://build.fhir.org/codesystem-administrative-gender.json",
        "AddressUse"=>"https://build.fhir.org/codesystem-address-use.json",
        "AddressType"=>"https://build.fhir.org/codesystem-address-type.json",
        "MaritalStatus"=>["https://terminology.hl7.org/3.1.0/CodeSystem-v3-MaritalStatus.json","https://terminology.hl7.org/3.1.0/CodeSystem-v3-NullFlavor.json"],
        "LinkType"=>"https://build.fhir.org/codesystem-link-type.json",
        "ProvenanceActivityType"=>["https://hl7.org/fhir/us/core/STU5/CodeSystem-us-core-provenance-participant-type.json","https://terminology.hl7.org/3.1.0/CodeSystem-provenance-participant-type.json"]
    ];

    public function getvalueset($ValueSet,$Term=null,$Sort=null)
    {
        if (array_key_exists($ValueSet,$this->valusets))
        {
            $client = new Client();
            $options= [];
            $urls= $this->valusets[$ValueSet];

            if (!is_array($urls))
            {
                $tmp= $urls;
                unset($urls);
                $urls[]= $tmp;
            }

            foreach ($urls as $url)
            {

                $response = $client->get($url);

                if ($response->getStatusCode()==200)
                {
                    $body= json_decode($response->getBody());

                    foreach ($body->concept as $concept)
                    {
                        // Is no defition is set we use Display as default
                        $definition= $concept->display;
                        if (isset($concept->definition))
                            $definition= $concept->definition;

                        $insert= true;

                        if (($Term!=null) && ($Term!="*"))
                        {
                            if (strpos(strtolower($concept->display),strtolower($Term))===false)
                                $insert= false;
                        }

                        if ($insert)
                            $options[] = [
                                "definition"=>$definition,
                                "value"=>$concept->code,
                                "name"=>$concept->display,
                            ];

                    }

                    if ($Sort!=null)
                        $options= collect($options)->sortBy($Sort,SORT_STRING)->values();


                }
                else
                    throw new InternalErrorException($response->getStatusCode());
            }


            return $options;
        }

        throw new NotFoundHttpException("ValueSet not found");
    }
}
