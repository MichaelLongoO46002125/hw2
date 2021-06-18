<?php

namespace App\Http\Controllers;

use Illuminate\Routing\Controller as BaseController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash;
use App\Models\Person;
use App\Models\Employee;

class AdminController extends BaseController
{
    public function signup()
    {
        if(AuthController::isAdmin())
            return view("admin.signup")->with('csrf_token', csrf_token())->with("authStatus", AuthController::authStatus());
        else
            abort(404);
    }

    public function checkSignup(Request $request)
    {
        if(AuthController::isAdmin())
        {
            if($request->has("from") && $request->from === "booking")
                return redirect()
                    ->route("admin/signup")
                    ->withInput();

            $validator = Validator::make($request->all(), [
                'name' => ['required'],
                'last_name' => ['required'],
                'tel' => [['regex', '/^[+]\d{1,15}$/']]
            ]);

            $job= strtoupper($request->job);

            $validator->after(function ($validator) {
                GLOBAL $request;

                $emailResponse = CheckController::checkEmail($request->email);

                if($emailResponse["ok"] === null)
                    $validator->errors()->add('email', true);
                else if($emailResponse["ok"] === true)
                    $validator->errors()->add('email2', true);

                switch(strtoupper($request->job))
                {
                    case config('hw.auth.user'):
                        if(!$request->filled(["name", "last_name", "email", "tel"]))
                            $validator->errors()->add('general', true);
                    break;

                    case config('hw.auth.admin'):
                    case config('hw.auth.bartender'):
                    case config('hw.auth.chef'):
                    case config('hw.auth.waiter'):
                        if(!$request->filled(["name", "last_name", "email", "tel", "salary", "duty_start", "duty_end"]))
                            $validator->errors()->add('general', true);

                        if(!preg_match("/(^(0|0.00)$)|(^[1-9]\d*(\.\d{1,2})?$)/", $request->salary))
                            $validator->errors()->add('salary', true);

                        if(!preg_match("/^(([0-1][0-9])|(2[0-3])):[0-5][0-9]$/", $request->duty_start))
                            $validator->errors()->add('duty_start', true);

                        if(!preg_match("/^(([0-1][0-9])|(2[0-3])):[0-5][0-9]$/", $request->duty_end))
                            $validator->errors()->add('duty_end', true);
                    break;

                    default:
                        $validator->errors()->add('job', true);
                    break;
                }
            });

            if($validator->fails())
            {
                $except= array();
                if($job === config('hw.auth.user'))
                    $except= ["salary", "duty_start", "duty_end"];

                return redirect()
                        ->route("admin/signup")
                        ->withErrors($validator)
                        ->withInput($request->except($except));
            }
            else
            {
                $pw= Hash::make("Prova123");
                $person = new Person;
                $person->name = $request->name;
                $person->last_name = $request->last_name;
                $person->email = strtolower($request->email);
                $person->phone_number = $request->tel;
                $person->password = $pw;
                $person->save();

                if($job !== config('hw.auth.user'))
                {
                    $employee = new Employee;
                    $employee->salary = $request->salary;
                    $employee->duty_start = $request->duty_start;
                    $employee->duty_end = $request->duty_end;
                    $employee->job = $job;
                    $person->employee()->save($employee);
                }

                return redirect()->route("home");
            }
        }
        else
            abort(404);
    }
}

?>
