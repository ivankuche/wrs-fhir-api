<?php

namespace App\Http\Controllers;

use DCarbone\PHPFHIRGenerated\R4\FHIRResource\FHIRDomainResource\FHIRPatient;
use GuzzleHttp\Exception\BadResponseException;
use Illuminate\Http\Request;
use ReflectionClass;

class CapabilitiesController extends Controller
{
    //
    public function index()
    {
        // Connect to HAPI FHIR Test server for testing purposes
        $type= ['Accept'=>'application/json'];

        $endpoint = "http://hapi.fhir.org/baseR4/metadata";
        $client = new \GuzzleHttp\Client(['base_uri'=>$endpoint,'headers'=>$type]);


        try
        {
            $response = json_decode($client->request('GET')->getBody()->getContents());
            dd($response);

        }
        catch(BadResponseException $e)
        {
            dd($e);
        }
        return $response;
    }

    public function exploreClass()
    {

        $patient= new DCarbone\PHPFHIRGenerated\R4\FHIRResource\FHIRDomainResource\FHIRPatient();
        $reflect= new ReflectionClass($patient);

        $props= $reflect->getProperties();
        echo "Properties: <br>";
        foreach ($props as $prop) {
            echo $prop->getName()."<br/>";
        }

        echo "<br><br>";

        $methods= $reflect->getMethods();
        echo "Methods: <br>";
        foreach ($methods as $method) {
            echo $method->getName()."<br/>";
        }
die();
        $methods= $reflect->getMethod();
        dd($props);

        dd(get_object_vars ("DCarbone\PHPFHIRGenerated\R4\FHIRResource\FHIRDomainResource\FHIRPatient"));
        dd(get_class_vars("DCarbone\PHPFHIRGenerated\R4\FHIRResource\FHIRDomainResource\FHIRPatient"));
        die("cpao");
    }

    public function aws()
    {
        // Connect to HAPI FHIR Test server for testing purposes
        $type= ['Accept'=>'application/json'];

        $endpoint = "https://raw.githubusercontent.com/awslabs/fhir-works-on-aws-routing/mainline/src/router/validation/schemas/fhir.schema.v4.json";
        $client = new \GuzzleHttp\Client(['base_uri'=>$endpoint,'headers'=>$type]);

        return $client->request('GET')->getBody()->getContents();
    }
}
