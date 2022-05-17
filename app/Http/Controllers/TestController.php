<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use GuzzleHttp\Client;

class TestController extends Controller
{
    //

    public function datasource()
    {


        $title= "Address type";
        $urls= [
            "https://build.fhir.org/codesystem-link-type.json"


        ];/*
            "http://terminology.hl7.org/3.1.0/CodeSystem-v3-NullFlavor.json"
        ];
*/

        $client = new Client();
        $options= [];

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

                //dd($body);
                foreach ($body->concept as $concept)
                {
                    $options[] = [
                        "title"=>$concept->definition,
                        "value"=>$concept->code,
                        "name"=>$concept->display,
                    ];
                }
            }
            else
                die("REST error code ".$response->getStatusCode());
        }

        return view("datasource", ["options"=>$options, "title"=>$title]);
    }
}
