<?php

namespace App\Http\Controllers;

use Illuminate\Routing\Controller as BaseController;
use Illuminate\Support\Facades\Validator;
use App\Models\Person;

class CheckController extends BaseController
{
    public static function checkEmail($email)
    {
        $email = strtolower($email);
        $validator = Validator::make(array('email' => $email), ['email' => ['required','email:filter']]);

        if($validator->fails())
            return array("ok" => null);
        else
        {
            $query = Person::where("email", $email)->first();

            if($query !== null)
                return array("ok" => true);

            return array("ok" => false);
        }
    }
}

?>
