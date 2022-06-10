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
        "ProvenanceActivityType"=>["https://hl7.org/fhir/us/core/STU5/CodeSystem-us-core-provenance-participant-type.json","https://terminology.hl7.org/3.1.0/CodeSystem-provenance-participant-type.json"],
        "ProvenanceEntityRole"=>"https://build.fhir.org/codesystem-provenance-entity-role.json",
        "SecurityRoleType"=>"https://build.fhir.org/codesystem-sample-security-structural-roles.json",
        "PatientContactRelationship"=>"https://terminology.hl7.org/3.1.0/CodeSystem-v2-0131.json",
        "ResourceType"=>"https://build.fhir.org/codesystem-resource-types.json",
        "AllergyIntoleranceClinicalStatusCodes"=>"https://terminology.hl7.org/3.1.0/CodeSystem-allergyintolerance-clinical.json",
        "AllergyIntoleranceVerificationStatus"=>"https://terminology.hl7.org/3.1.0/CodeSystem-allergyintolerance-verification.json",
        "AllergyIntoleranceType"=>"https://build.fhir.org/codesystem-allergy-intolerance-type.json",
        "AllergyIntoleranceCategory"=>"https://build.fhir.org/codesystem-allergy-intolerance-category.json",
        "AllergyIntoleranceCriticality"=>"https://build.fhir.org/codesystem-allergy-intolerance-criticality.json",
        "AllergyIntoleranceSeverity"=>"https://build.fhir.org/codesystem-reaction-event-severity.json",
        "BirthSex"=>"https://terminology.hl7.org/3.1.0/CodeSystem-v3-AdministrativeGender.json",
        "RequestStatus"=>"https://build.fhir.org/codesystem-request-status.json",
        "CarePlanIntent"=>"https://www.hl7.org/fhir/codesystem-request-intent.json",
        "CarePlanActivityKind"=>null,
        "CarePlanActivityStatus"=>"https://build.fhir.org/codesystem-care-plan-activity-status.json",
        "CareTeamStatus"=>"https://build.fhir.org/codesystem-care-team-status.json",
        "ConditionClinicalStatusCodes"=>"https://terminology.hl7.org/3.1.0/CodeSystem-condition-clinical.json",
        "ConditionVerificationStatus"=>"https://build.fhir.org/codesystem-condition-ver-status.json",
        "UDIEntryType"=>"https://build.fhir.org/codesystem-udi-entry-type.json",
        "DeviceNameType"=>"https://build.fhir.org/codesystem-device-nametype.json",
        "FHIRDeviceStatus"=>"https://build.fhir.org/codesystem-device-status.json",
        "FHIRDeviceStatusReason"=>"https://fhir-ru.github.io/codesystem-device-status-reason.json",
        "FHIRDeviceSpecializationCategory"=>"https://build.fhir.org/codesystem-device-specialization-category.json",
        "FHIRDeviceOperationalStatus"=>"https://build.fhir.org/codesystem-device-operationalstatus.json",
        "DeviceRelationType"=>"https://build.fhir.org/codesystem-device-relationtype.json",
        "DiagnosticReportStatus"=>"https://build.fhir.org/codesystem-diagnostic-report-status.json",
        "USCoreProvenancePaticipantTypeCodes"=>["https://hl7.org/fhir/us/core/STU4/CodeSystem-us-core-provenance-participant-type.json","https://terminology.hl7.org/1.0.0//CodeSystem-provenance-participant-type.json"],
        "DocumentReferenceStatus"=>"https://build.fhir.org/codesystem-document-reference-status.json",
        "CompositionStatus"=>"https://build.fhir.org/codesystem-composition-status.json",
        "USCoreDocumentReferenceCategory"=>"https://hl7.org/fhir/us/core/STU4/CodeSystem-us-core-documentreference-category.json",
        "v3CodeSystemActCode"=>"https://terminology.hl7.org/3.1.0/CodeSystem-v3-ActCode.json",
        "DocumentAttestationMode"=>"https://build.fhir.org/codesystem-composition-attestation-mode.json",
        "DocumentRelationshipType"=>"https://build.fhir.org/codesystem-document-relationship-type.json",
        "DocumentReferenceFormatCodeSet"=>"https://profiles.ihe.net/fhir/ihe.formatcode.fhir/1.1.0/CodeSystem-formatcode.json",
        "GoalAchievementStatus"=>"https://terminology.hl7.org/3.1.0/CodeSystem-goal-achievement.json",
        "GoalCategory"=>"https://terminology.hl7.org/3.1.0/CodeSystem-goal-category.json",
        "GoalPriority"=>"https://terminology.hl7.org/3.1.0/CodeSystem-goal-priority.json",
        "ImmunizationSubpotentReason"=>"https://terminology.hl7.org/3.1.0/CodeSystem-immunization-subpotent-reason.json",
        "ImmunizationProgramEligibility"=>"https://terminology.hl7.org/3.1.0/CodeSystem-immunization-program-eligibility.json",
        "ImmunizationFundingSource"=>"https://terminology.hl7.org/3.1.0/CodeSystem-immunization-funding-source.json",
        "medicationRequestStatus"=>"https://build.fhir.org/codesystem-medicationrequest-status.json",
        "medicationRequestStatusReasonCodes"=>"https://terminology.hl7.org/3.1.0/CodeSystem-medicationrequest-status-reason.json",
        "medicationRequestIntent"=>"https://build.fhir.org/codesystem-medicationrequest-intent.json",
        "medicationRequestAdministrationLocationCodes"=>"https://terminology.hl7.org/3.1.0/CodeSystem-medicationrequest-admin-location.json",
        "RequestPriority"=>"https://build.fhir.org/codesystem-request-priority.json",
        "MedicationIntendedPerformerRole"=>"https://build.fhir.org/codesystem-medication-intended-performer-role.json",




    ];

    public function getValueSet($ValueSet,$Term=null,$Sort=null)
    {
        if (array_key_exists($ValueSet,$this->valusets))
        {
            $client = new Client();
            $options= [];
            $urls= $this->valusets[$ValueSet];
            $urlsMeta= [];

            if (!is_array($urls))
            {
                $tmp= $urls;
                unset($urls);
                $urls[]= $tmp;
            }

            foreach ($urls as $url)
            {

                if ($url!=null)
                {
                    $response = $client->get($url);

                    if ($response->getStatusCode()==200)
                    {
                        $body= json_decode($response->getBody());
                        $concepts= $body->concept;
                        unset($body->concept,$body->text);
                        $urlsMeta[$body->id]= $body;

                        $this->recursiveFilling($concepts,$body->id,$options,$Term);

                    }
                    else
                        throw new InternalErrorException($response->getStatusCode());
                }


            }

            // Special cases where extra info must be placed
            switch ($ValueSet)
            {

                case "BirthSex":

                    $this->addCustomValues($options,
                        "https://terminology.hl7.org/3.1.0/CodeSystem-v3-NullFlavor.json",
                        ["UNK"],
                        $Term);

                    break;

                case "CarePlanActivityKind":
                    $this->addCustomValues($options,
                        "https://www.hl7.org/fhir/codesystem-resource-types.json",
                        ["Appointment","CommunicationRequest","DeviceRequest","MedicationRequest","NutritionOrder",
                            "Task","ServiceRequest","VisionPrescription"],
                        $Term);

                    break;
            }

            if ($Sort!=null)
                $options= collect($options)->sortBy($Sort,SORT_STRING)->values();


            unset($body->concept,$body->text);
            return [
                "meta"=>$urlsMeta,
                "data"=>$options
            ];
        }

        throw new NotFoundHttpException("ValueSet not found");
    }

    private function recursiveFilling($Source,$SourceID,&$Destination,$Term)
    {
        foreach ($Source as $concept)
        {
            // Is no defition is set we use Display or Code as default
            $definition= $concept->code;

            if (isset($concept->display))
                $definition=$concept->display;

            if (isset($concept->definition))
                $definition= $concept->definition;

            $insert= true;

            if (($Term!=null) && ($Term!="*"))
            {
                if (strpos(strtolower($concept->code),strtolower($Term))===false)
                    $insert= false;
            }

            $newElement= [
                "definition"=>$definition,
                "value"=>$concept->code,
                "name"=>(isset($concept->display)==true) ? $concept->display : $concept->code,
                "source"=>$SourceID,
            ];

            if (isset($concept->concept))
                $this->recursiveFilling($concept->concept,$SourceID,$newElement["children"],$Term);

            if ($insert)
                $Destination[] = $newElement;

        }

    }

    private function findRecursiveElement($Source,$key,$value)
    {
        foreach ($Source as $element)
        {
            if ((isset($element->$key)) && ($element->$key==$value))
            {
                $definition= $element->display;
                if (isset($element->definition))
                    $definition= $element->definition;

                return [
                    "definition"=>$definition,
                    "value"=>$element->code,
                    "name"=>$element->display,
                ];
            }
            else{
                if (isset($element->concept))
                    return $this->findRecursiveElement($element->concept,$key,$value);
            }
        }

    }

    private function addCustomValues(&$SourceValues,$url,$customValues,$Term)
    {

        $client = new Client();
        $response = $client->get($url);

        if ($response->getStatusCode()==200)
        {
            // Lower case of the Custom Values
            $search_array = array_map('strtolower', $customValues);
            $body= json_decode($response->getBody());


            foreach ($customValues as $customValue)
            {
                $concept= $this->findRecursiveElement($body->concept,"code",$customValue);

                if ($concept!=[])
                {
                    $insert= true;

                    if (($Term!=null) && ($Term!="*"))
                    {
                        if (strpos(strtolower($concept["value"]),strtolower($Term))===false)
                            $insert= false;
                    }

                    if ($insert)
                        $SourceValues[] = $concept;
                }
            }
        }
        else
            throw new InternalErrorException($response->getStatusCode());

    }

    public function getMethods()
    {
        return array_keys($this->valusets);
    }

}
