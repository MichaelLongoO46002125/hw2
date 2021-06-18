<?php

namespace App\Http\Controllers;

use Illuminate\Routing\Controller as BaseController;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash;
use App\Models\MyCookie;
use App\Models\Person;

class AuthController extends BaseController
{
    public function login()
    {
        if(self::checkAuth())
            return redirect(url()->previous());
        else
            return view("login")->with('csrf_token', csrf_token())->with("authStatus", AuthController::authStatus());
    }

    public function checkLogin(Request $request)
    {
        if(self::checkAuth())
            return redirect(url()->previous());
        else
        {
            if($request->isNotFilled("email") || $request->isNotFilled("password"))
            {
                $validator = Validator::make(array(), array());
                $validator->errors()->add("general", true);

                return redirect()
                        ->route("login")
                        ->withErrors($validator)
                        ->withInput($request->except(['password']));
            }

            $validator = Validator::make($request->all(), [
                "email" => "email:filter"
            ]);

            if($validator->fails())
            {
                return redirect()
                        ->route("login")
                        ->withErrors($validator)
                        ->withInput($request->except(['password']));
            }
            else
            {
                $person = Person::where("email", $request->email)->first();

                if($person === null)
                {
                    $validator->errors()->add("email", true);
                    return redirect()
                            ->route("login")
                            ->withErrors($validator)
                            ->withInput($request->except(['password']));
                }

                if(Hash::check($request->password, $person->password))
                {

                    $employee = $person->employee()->first();

                    session([
                        "person_id" => $person->id,
                        "email" => $person->email,
                        "job" => ($employee ? $employee->job : config('hw.auth.user'))
                    ]);

                    if($request->filled("remember"))
                    {
                        $minutes= 43200; //30giorni = 30*24*60 minuti

                        $token = random_bytes(16);
                        $token= Hash::make($token);
                        $expires = date("Y-m-d", strtotime("+30 day"));

                        $cookie = new MyCookie;
                        $cookie->token = $token;
                        $cookie->expires = $expires;
                        $cookie->person_id = $person->id;
                        $cookie->save();

                        Cookie::queue('hw_cookie_id', $cookie->id, $minutes);
                        Cookie::queue('hw_cookie_token', $token, $minutes);
                    }

                    return redirect()->route("home");
                }
                else
                {
                    $validator->errors()->add("password", true);
                    return redirect()
                                ->route("login")
                                ->withErrors($validator)
                                ->withInput($request->except(['password']));
                }
            }
        }
    }

    public function logout()
    {
        session()->flush();

        if(Cookie::has("hw_cookie_id") && Cookie::has("hw_cookie_token"))
        {
            $cookie = MyCookie::find(Cookie::get("hw_cookie_id"));

            if(Cookie::get("hw_cookie_token") === $cookie->token)
                $cookie->delete();

            Cookie::expire("hw_cookie_id");
            Cookie::expire("hw_cookie_token");
        }

        return redirect()->route("home");
    }

    public static function checkAuth()
    {
        if(session()->has('person_id') && session()->has('email') && session()->has('job'))
            return true;
        else
        {
            if(Cookie::has("hw_cookie_id") && Cookie::has("hw_cookie_token"))
            {
                $cookie = MyCookie::find(Cookie::get("hw_cookie_id"));

                if($cookie !== null)
                {
                    if(Cookie::get("hw_cookie_token") !== $cookie->token)
                    {
                        return false;
                    }

                    if(date("Y-m-d") > $cookie->expires)
                    {
                        Cookie::expire("hw_cookie_id");
                        Cookie::expire("hw_cookie_token");
                    }
                    else
                    {
                        $person= Person::find($cookie->person_id);

                        if($person !== null)
                        {
                            $employee = $person->employee()->first();

                            session([
                                "person_id" => $person->id,
                                "email" => $person->email,
                                "job" => ($employee ? $employee->job : config('hw.auth.user'))
                            ]);

                            return true;
                        }
                    }
                }
            }

            return false;
        }
    }

    public static function getID()
    {
        if(self::checkAuth())
            return session("person_id");

        return null;
    }

    public static function getEmail()
    {
        if(self::checkAuth())
            return session("email");

        return null;
    }

    public static function isAdmin()
    {
        return (self::checkAuth() && session("job") === config('hw.auth.admin'));
    }

    public static function isUser()
    {
        return (self::checkAuth() && session("job") === config('hw.auth.user'));
    }

    public static function isChef()
    {
        return (self::checkAuth() && session("job") === config('hw.auth.chef'));
    }

    public static function isWaiter()
    {
        return (self::checkAuth() && session("job") === config('hw.auth.waiter'));
    }

    public static function isBartender()
    {
        return (self::checkAuth() && session("job") === config('hw.auth.bartender'));
    }

    public static function authStatus()
    {
        $status = [
            "authenticated" => false,
            "isAdmin" => false,
            "isUser" => false,
            "isBartender" => false,
            "isChef" => false,
            "isWaiter" => false,
            "id" => null,
            "email" => null
        ];

        if(self::checkAuth())
        {
            $status["authenticated"] = true;

            switch(session("job"))
            {
                case config("hw.auth.user"):
                    $status["isUser"] = true;
                break;

                case config("hw.auth.admin"):
                    $status["isAdmin"] = true;
                break;

                case config("hw.auth.bartender"):
                    $stauts["isBartender"] = true;
                break;

                case config("hw.auth.chef"):
                    $status["isChef"] = true;
                break;

                case config("hw.auth.waiter"):
                    $status["isWaiter"] = true;
                break;
            }

            $status["id"] = session("person_id");
            $status["email"] = session("email");
        }

        return $status;
    }
}

?>
