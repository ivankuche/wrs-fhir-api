<?php

namespace App\Http\Controllers;

use App\Models\Patient;
use Illuminate\Http\Request;
use Symfony\Component\Console\Input\Input;

use function PHPUnit\Framework\returnSelf;

class PatientController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

    public function index(Request $request)
    {

        $patients= Patient::inRandomOrder()->limit(100)->get();


        if (!$request->wantsJson())
            return response()->xml($patients);
        else
            return $patients;
    }

    public function pagination(Request $request)
    {
        $patients= Patient::inRandomOrder()->paginate(5);//->get();


        if (!$request->wantsJson())
            return response()->xml($patients);
        else
            return $patients;
    }

    public function getmodified(Request $request)
    {
        $patients= Patient::inRandomOrder()->limit(100)->get();

        foreach ($patients as $id=>$data)
        {
            $patients[$id]['nationality']='USA';
        }


        if (!$request->wantsJson())
            return response()->xml($patients);
        else
            return $patients;
    }


    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
        die("para");
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        return Patient::create(request()->all());
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Patient  $patient
     * @return \Illuminate\Http\Response
     */
    public function show(Patient $patient)
    {
        //
        die("parea");
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Patient  $patient
     * @return \Illuminate\Http\Response
     */
    public function edit(Patient $patient)
    {
        die("nerek");
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Patient  $patient
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Patient $patient)
    {
        $patient->update($request->all());
        return $patient;
                //
        //die("nereka");
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Patient  $patientnereka
     * @return \Illuminate\Http\Response
     */
    public function destroy(Patient $patient)
    {
        //
        die("pearaer");
    }

    public function recursive()
    {

        $type= ['Accept'=>'application/xml'];
        if (request()->wantsJson())
            $type= ['Accept'=>'application/json'];

        $endpoint = "http://wrs.test/patient";
        $client = new \GuzzleHttp\Client(['base_uri'=>$endpoint,'headers'=>$type]);


        $response = $client->request('GET');//, $endpoint);
        return $response->getBody();

        /*
        if (!$request->wantsJson())
            return response()->xml($patients);
        else
            return $patients;

        // url will be: http://my.domain.com/test.php?key1=5&key2=ABC;

        $statusCode = $response->getStatusCode();
        $content = $response->getBody();

        die($content);
        */

    }
}
