<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Exception;
use GuzzleHttp\Client;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\CssSelector\Exception\InternalErrorException;
use Illuminate\Support\Facades\File;

class InfoController extends Controller
{

    private function parseResponse($elements)
    {
        $response= [];

        foreach ($elements as $element)
        {
            try
            {
                $response[]= $element->pt->term;
            }
            catch (Exception $e)
            {
            }
        }

        return collect($response);
    }


    private function queryConcept($concept)
    {
        $client = new Client();

        $url= "https://snowstorm.msal.gov.ar/MAIN/concepts";

        // Future WRS endpoint
        //$url= "https://snowstorm.wrs.cloud/MAIN/concepts";

        $params= [
            "query"=>[
//                'activeFilter'=>true,
 //               'termActive'=>true,
                'language'=>'en',
                'ecl'=>$concept,
//                'active'=>true,
                'limit'=>200
            ],
            "headers"=> [
                "Accept-Language"=>"en"
            ]
        ];

        try
        {
            $response= $client->request('GET',$url,$params);

            if ($response->getStatusCode()==200)
            {
                $body= json_decode($response->getBody());

                $return= $this->parseResponse($body->items);

                return $return;
            }
            else
                throw new InternalErrorException($response->getStatusCode());

        }
        catch (Exception $e)
        {
                throw new InternalErrorException($e->getMessage());
        }
    }


    private function directQuery($conceptID)
    {
        $client = new Client();

        $url= "https://connect.medlineplus.gov/service?mainSearchCriteria.v.cs=2.16.840.1.113883.6.96&informationRecipient.languageCode.c=en&mainSearchCriteria.v.c=".$conceptID;

        try
        {
            $response= $client->get($url);

            if ($response->getStatusCode()==200)
            {

                $body= $response->getBody()->getContents();


                $xml = simplexml_load_string($body);

                $json = json_encode($xml);

                $array = json_decode($json,TRUE);

//                print_r($array["entry"]);

                echo $array["entry"]["summary"];
                die();


                print_r($array);
                die();

                //$return= $this->parseResponse($body->items);

                //return $return;
            }
            else
                throw new InternalErrorException($response->getStatusCode());

        }
        catch (Exception $e)
        {
            die("Info not found, please check the concept id");
        }
    }

    public function getinfo($Concept)
    {
        $this->directQuery($Concept);
        die("capo");

        $terms= $this->queryConcept($Concept)->toArray();



        print_r($terms);

        die();
        die("capo");
    }




    /**
     * CCDA Mapping
     */

    private function getName($node) {

        return  [
            "use"=>(string) $node->name->attributes()->use,
            "text"=>trim((string) implode(" ",
                [
                    (string) $node->name->prefix,
                    implode (" ",(array) $node->name->given),
                    implode (" ",(array) $node->name->family),
                    (string) $node->name->suffix,
                ])),
            "family" => implode (" ",(array) $node->name->family),
            "given" => implode (" ",(array) $node->name->given),
            "prefix" => (string) $node->name->prefix,
            "suffix" => (string) $node->name->suffix,
        ];
    }

    private function getTelecom($node)
    {
        return [
            "use"=>(string) $node->attributes()->use,
            "value"=>(string) $node->attributes()->value,
        ];
    }

    private function getAddress($node)
    {
        $textAddress= (string) implode(", ",
            $this->filterEmptyValues([
                (string) $node->streetAddressLine,
                (string) $node->city,
                (string) $node->state,
                (string) $node->country
            ])
        );

        $line= (string) implode(" ",
            $this->filterEmptyValues([
                (string) $node->streetAddressLine,
                (string) $node->city,
                (string) $node->state,
                (string) $node->country,
                (string) $node->postalCode
            ])
        );

         return [
            "use"=>(string) $node->attributes()->use,
            "type"=>"both", // physical and postal
            "text"=>$textAddress,
            "line"=>$line,
            "city"=>(string) $node->city,
            "state"=>(string) $node->state,
            "postalCode"=>(string) $node->postalCode,
            "country"=>(string) $node->country
        ];
    }

    private function patientMapper(&$patient,$field, $data)
    {

        switch (strtolower($field))
        {
            case "id":
                $patient["identifier"][]= [
                    "system"=>(string) $data->attributes()->root,
                    "value"=>(string) $data->attributes()->extension,
                ];
                break;

            case "patient":
                $patient["name"][]= $this->getName($data);
                $patient["gender"] = (string) $data->administrativeGenderCode->attributes()->displayName;
                $patient["contact"][]= [
                    "relationship"=>[
                        "coding"=>[
                            "system"=>(string) $data->guardian->code->attributes()->codeSystem,
                            "code"=>(string) $data->guardian->code->attributes()->code,
                        ]
                    ],
                    "name"=>[$this->getName($data->guardian->guardianPerson)],
                    "telecom"=>[$this->getTelecom($data->guardian->telecom)],
                    "address"=>[$this->getAddress($data->guardian->addr)],
                    "gender"=>"unknown", // hardcoded
                ];
                $patient["communication"][]= [
                    "language"=>[
                        "coding"=>[
                            "system"=>"urn:ietf:bcp:47",
                            "code"=>(string)$data->languageCommunication->languageCode->attributes()->code,
                        ]
                    ],
                    "preferred"=>(string)$data->languageCommunication->preferenceInd->attributes()->value,
                ];

                $patient["extension"][]= [
                    "extension"=>[
                        [
                            "url"=>"ombCategory",
                            "valueCoding"=> [
                                "system" =>"urn:oid:2.16.840.1.113883.6.238",
                                "code" =>(isset($data->raceCode->attributes()->nullFlavor)?(string)$data->raceCode->attributes()->nullFlavor:(string)$data->raceCode->attributes()->displayName),
                            ]
                        ],
                    ],
                    "url"=>"http://hl7.org/fhir/us/core/StructureDefinition/us-core-race",
                ];

                $patient["extension"][]= [
                    "extension"=>[
                        [
                            "url"=>"ombCategory",
                            "valueCoding"=> [
                                "system" =>"urn:oid:2.16.840.1.113883.6.238",
                                "code" =>(isset($data->ethnicGroupCode->attributes()->nullFlavor)?(string)$data->ethnicGroupCode->attributes()->nullFlavor:(string)$data->racethnicGroupCodeeCode->attributes()->displayName),
                            ]
                        ],
                    ],
                    "url"=>"http://hl7.org/fhir/us/core/StructureDefinition/us-core-ethnicity",
                ];

                break;

            case "telecom":
                $patient["telecom"][]= $this->getTelecom($data);
                break;

            case "addr":
                $patient["address"][]= $this->getAddress($data);
                break;

        }
    }

    private function filterEmptyValues($input)
    {
      foreach ($input as &$value)
      {
        if (is_array($value))
        {
          $value = $this->filterEmptyValues($value);
        }
      }

      return array_filter($input);
    }

    private function fillPatient($node)
    {
        $patient= null;
        foreach ($node->children() as $valueList)
        {
            $this->patientMapper($patient,$valueList->getName(),$valueList);
        }

        return ($this->filterEmptyValues($patient));
    }


    private function encounterMapper(&$encounter,$field, $data)
    {

        switch (strtolower($field))
        {
            case "id":
                $encounter["identifier"][]= [
                    "value"=>(string) $data->attributes()->root,
                ];
                break;

            case "encounter":
                $encounter["name"][]= $this->getName($data);
                $encounter["gender"] = (string) $data->administrativeGenderCode->attributes()->displayName;
                $encounter["contact"][]= [
                    "relationship"=>[
                        "coding"=>[
                            "system"=>(string) $data->guardian->code->attributes()->codeSystem,
                            "code"=>(string) $data->guardian->code->attributes()->code,
                        ]
                    ],
                    "name"=>[$this->getName($data->guardian->guardianPerson)],
                    "telecom"=>[$this->getTelecom($data->guardian->telecom)],
                    "address"=>[$this->getAddress($data->guardian->addr)],
                    "gender"=>"unknown", // hardcoded
                ];
                $encounter["communication"][]= [
                    "language"=>[
                        "coding"=>[
                            "system"=>"urn:ietf:bcp:47",
                            "code"=>(string)$data->languageCommunication->languageCode->attributes()->code,
                        ]
                    ],
                    "preferred"=>(string)$data->languageCommunication->preferenceInd->attributes()->value,
                ];

                $encounter["extension"][]= [
                    "extension"=>[
                        [
                            "url"=>"ombCategory",
                            "valueCoding"=> [
                                "system" =>"urn:oid:2.16.840.1.113883.6.238",
                                "code" =>(isset($data->raceCode->attributes()->nullFlavor)?(string)$data->raceCode->attributes()->nullFlavor:(string)$data->raceCode->attributes()->displayName),
                            ]
                        ],
                    ],
                    "url"=>"http://hl7.org/fhir/us/core/StructureDefinition/us-core-race",
                ];

                $encounter["extension"][]= [
                    "extension"=>[
                        [
                            "url"=>"ombCategory",
                            "valueCoding"=> [
                                "system" =>"urn:oid:2.16.840.1.113883.6.238",
                                "code" =>(isset($data->ethnicGroupCode->attributes()->nullFlavor)?(string)$data->ethnicGroupCode->attributes()->nullFlavor:(string)$data->racethnicGroupCodeeCode->attributes()->displayName),
                            ]
                        ],
                    ],
                    "url"=>"http://hl7.org/fhir/us/core/StructureDefinition/us-core-ethnicity",
                ];

                break;

            case "telecom":
                $encounter["telecom"][]= $this->getTelecom($data);
                break;

            case "addr":
                $encounter["address"][]= $this->getAddress($data);
                break;

        }
    }

    private function fillEncounter($node) {
        $encounter= null;
        dd($node);
        foreach ($node->children() as $valueList)
        {
            $this->encounterMapper($encounter,$valueList->getName(),$valueList);
        }

        dd($this->filterEmptyValues($encounter));
        return ($this->filterEmptyValues($encounter));

    }

    public function parseCCDA()
    {

        $xml = simplexml_load_string(File::get('CCD_Alexa_Test_07_06_2022_12_06_50.xml'));

        // Hidding unnecesary data
        unset($xml->realmCode);
        unset($xml->typeId);
        unset($xml->title);
        unset($xml->templateId);
        unset($xml->id);
        unset($xml->languageCode);
        unset($xml->code);
        unset($xml->effectiveTime);
        unset($xml->confidentialityCode);

        $patient= $this->fillPatient($xml->recordTarget->patientRole);
        unset($xml->recordTarget);

        $encounter= null;

        if (isset($xml->componentOf->encompassingEncounter))
            $encounter= $this->fillEncounter($xml->componentOf->encompassingEncounter);

        dd($encounter);
        dd($xml);

        die("FINALE");
    }

}
