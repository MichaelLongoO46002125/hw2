<?php

namespace App\Http\Controllers;

use Illuminate\Routing\Controller as BaseController;

class HomeController extends BaseController
{
    public function home()
    {
        return view("home")->with("csrf_token", csrf_token())->with("authStatus", AuthController::authStatus());
    }
}

?>
