<?php

namespace App\Http\Controllers;

use Exception;
use Illuminate\Http\Request;
use GuzzleHttp\Client;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\CssSelector\Exception\InternalErrorException;


class SnomedCTController extends Controller
{
    private $ValueSets= [
        "SubstanceCode"=>"<!105590001",
        "ClinicalFindings"=>"<!404684003",
        "RouteFindings"=>"<!284009009",
        "CarePlanCategory"=>"<!734163000",
        "CarePlanActivityPerformed"=>"<!397640006",
        "ProcedureCodes"=>"<71388002",
        "ParticipantRoles"=>"<223366009 OR <224930009",
        "BodyStructures"=>"<442083009",
        "DeviceType"=>"<49062001",
        "ImmunizationReason"=>"<!429060002", // Should be "<429060002 OR <281657000" but 281657000 is deprecated
        "MedicationCodes"=>"<!763158003",
        "ConditionProblemDiagnosis"=>"<!404684003 OR <!160245001",
        "AdditionalDosageInstructions"=>"<!419492006",
        "MedicationAsNeededReasonCodes"=>"<!404684003",
        "AnatomicalStructureForAdministrationSiteCodes"=>"<!91723000",
        "AdministrationMethodCodes"=>"<!736665006",
        "FormCodes"=>"<!736542009",
        "ProcedureNotPerformedReason"=>"<!183932001 OR <!416406003 OR <!416237000 OR <!428119001 OR <!416432009 OR <!394908001", // Removed 416064006 and 183944003 as deprecated
        "ProcedureCategoryCodes"=>"<!24642003 OR <!409063005 OR <!409073007 OR <!387713003 OR <!103693007 OR <!46947000 OR <!410606002",
        "ProcedurePerformerRoleCodes"=>"<!223366009",
        "ProcedureReasonCodes"=>"<<404684003 OR <<71388002",
        "ProcedureOutcomeCodes"=>"<<385669000 OR <<385671000 OR <<385670004",
        "ProcedureFollowUpCodes"=>"<<18949003 OR <<30549001 OR <<241031001 OR <<35963001 OR <<225164002 OR <<447346005 OR <<229506003 OR <<274441001 OR <<394725008 OR <<359825008",
        "ProcedureDeviceActionCodes"=>"<<129264002",
        "FHIRDeviceTypes"=>"<<49062001",



        // Items removed due to inactive in SNOMED: <<267425008 OR <<190753003 OR <<413427002. Make script to check invalid concepts.
        // Addedd <<105590001 as defined in https://build.fhir.org/valueset-substance-code.html
        "AllergyIntoleranceSubstanceProductConditionandNegationCodes"=>"<<418038007 OR <<29736007 OR <<340519003 OR <<716186003 OR <<105590001",

        "CarePlanActivityStatusReason"=>"<!183932001 OR <!416406003 OR <!416237000 OR <!428119001 OR <!416432009 OR <!394908001 OR 410536001", // Removed 416064006 and 183944003 as deprecated

    ];


    private function parseResponse($elements)
    {
        $response= [];

        foreach ($elements as $element)
        {
            try
            {
                $response[]= [
                    "conceptId"=>$element->conceptId,
                    "FSN"=>$element->fsn->term,
                    "name"=>$element->pt->term,
                ];
            }
            catch (Exception $e)
            {
            }
        }

        return collect($response);
    }

    private function queryConcept($concept,$term=null,$sort=null)
    {
        $client = new Client();

        $url= "https://snowstorm.msal.gov.ar/MAIN/concepts";

        // Future WRS endpoint
        //$url= "https://snowstorm.wrs.cloud/MAIN/concepts";

        $params= [
            "query"=>[
                'activeFilter'=>true,
                'termActive'=>true,
                'language'=>'en',
                'ecl'=>$concept,
                'active'=>true,
                'limit'=>200
            ],
            "headers"=> [
                "Accept-Language"=>"en"
            ]
        ];

        if (($term!=null) && ($term!="*"))
            $params["query"]["term"]= $term;

        $response= $client->request('GET',$url,$params);

        if ($response->getStatusCode()==200)
        {
            $body= json_decode($response->getBody());

            $return= $this->parseResponse($body->items);

            if ($sort!=null)
                $return= $return->sortBy($sort,SORT_STRING)->values();

            return $return;
        }
        else
            throw new InternalErrorException($response->getStatusCode());

    }

    private function releaseStatus()
    {

        $client = new Client();

        $url= "https://snowstorm.msal.gov.ar/branches/MAIN";

        // Future WRS endpoint
        //$url= "https://snowstorm.wrs.cloud/branches/MAIN";


        $response= $client->request('GET',$url);

        if ($response->getStatusCode()==200)
        {
            $body= json_decode($response->getBody());

            return $body;
        }
        else
            throw new InternalErrorException($response->getStatusCode());
    }

    public function query($ConceptGroup,$Term=null,$Sort=null)
    {
        if (array_key_exists($ConceptGroup,$this->ValueSets))
        {

            $return= [];

            if (is_array($this->ValueSets[$ConceptGroup]))
            {
                foreach ($this->ValueSets[$ConceptGroup] as $group)
                    $return= array_merge($return, $this->queryConcept($group,$Term)->values()->toArray());

                    if ($Sort!=null)
                        $return= collect($return)->sortBy($Sort,SORT_STRING)->values();

        }
            else
                $return= $this->queryConcept($this->ValueSets[$ConceptGroup],$Term,$Sort);

            return [
                "meta"=>$this->releaseStatus(),
                "data"=>$return
            ];
        }

        throw new NotFoundHttpException("ValueSet not found");
    }

    public function getMethods()
    {
        return array_keys($this->ValueSets);
    }

}
