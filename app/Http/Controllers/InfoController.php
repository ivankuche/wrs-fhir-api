<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Exception;
use GuzzleHttp\Client;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\CssSelector\Exception\InternalErrorException;

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

                $return= $this->parseResponse($body->items);

                return $return;
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
}
