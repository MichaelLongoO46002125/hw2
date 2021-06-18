<?php

namespace App\Http\Controllers;

use Illuminate\Routing\Controller as BaseController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash;
use App\Models\Person;

class SignupController extends BaseController
{
    public function signup()
    {
        if(AuthController::checkAuth())
            return redirect(url()->previous());
        else
            return view("signup")->with('csrf_token', csrf_token())->with("authStatus", AuthController::authStatus());
    }

    public function checkSignup(Request $request)
    {
        if(AuthController::checkAuth())
            return redirect(url()->previous());
        else
        {
            $validator = Validator::make($request->all(), [
                'name' => ['required'],
                'last_name' => ['required'],
                'tel' => [['regex', '/^[+]\d{1,15}$/']],
                'pw' => ['required', 'min:8'],
            ]);

            $validator->after(function ($validator) {
                GLOBAL $request;

                if(!$request->filled(["name", "last_name", "email", "tel", "pw", "cpw"]))
                    $validator->errors()->add('general', true);

                if( !preg_match("/[A-Z]/", $request->pw) ||
                    !preg_match("/[a-z]/", $request->pw) ||
                    !preg_match("/[0-9]/", $request->pw)
                )
                    $validator->errors()->add('pw2', true);

                if(strcmp($request->pw, $request->cpw) !== 0)
                    $validator->errors()->add('cpw', true);

                $emailResponse = CheckController::checkEmail($request->email);

                if($emailResponse["ok"] === null)
                    $validator->errors()->add('email', true);
                else if($emailResponse["ok"] === true)
                    $validator->errors()->add('email2', true);
            });

            if($validator->fails())
            {
                return redirect()
                        ->route("signup")
                        ->withErrors($validator)
                        ->withInput($request->except(['pw', 'cpw']));
            }
            else
            {
                $person = new Person;
                $person->name = $request->name;
                $person->last_name = $request->last_name;
                $person->email = strtolower($request->email);
                $person->phone_number = $request->tel;
                $person->password = Hash::make($request->pw);
                $person->save();
                return redirect()->route("login");
            }
        }
    }
}

?>
