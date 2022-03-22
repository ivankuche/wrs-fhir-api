<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class AuthController extends Controller
{
    //

    public function auth()
    {
        return true;
    }

    public function deny()
    {
        return false;
    }

    public function authorization(){
        return true;
    }

    public function denyauthorization(){
        return false;
    }


}
