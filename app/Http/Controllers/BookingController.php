<?php

namespace App\Http\Controllers;

use Illuminate\Routing\Controller as BaseController;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;
use App\Models\Room;
use App\Models\Rent;
use App\Models\Person;

class BookingController extends BaseController
{
    public function booking()
    {
        return view("booking")->with('csrf_token', csrf_token())->with("authStatus", AuthController::authStatus());
    }

    public function fetchRooms($check_in = null, $check_out = null, $person_num = null,
                               $matrimonial = null, $single = null, $min_fee = null, $max_fee = null
    )
    {
        $validator = Validator::make(
            [
                "check_in"  => $check_in,
                "check_out" => $check_out,
                "person_num" => $person_num,
                "matrimonial" => $matrimonial,
                "single" => $single,
                "min_fee" => $min_fee,
                "max_fee" => $max_fee
            ],
            [
                "check_in"  => ["required", "date_format:Y-m-d"],
                "check_out" => ["required", "date_format:Y-m-d", "after:check_in"],
                "person_num" => ["required", "integer", "min:1", "max:10"],
                "matrimonial" => ["required", "boolean"],
                "single" => ["required", "boolean"],
                "min_fee" => ["nullable", "numeric"],
                "max_fee" => ["nullable", "numeric", "gt:min_fee"]
            ]
        );

        $validator->stopOnFirstFailure(); //Funziona

        if($validator->fails())
        {
            if( $check_in !== null || $check_out !== null || $person_num !== null ||
                $matrimonial !== null || $single !== null || $min_fee !== null || $max_fee !== null
            )
                return array("ok" => null);

            $rooms = Room::all();
        }
        else
        {
            $rooms = Room::whereNotIn(
                "room_number",
                Rent::select("room_id")
                    ->where([
                        ["check_in", "<=", $check_in],
                        ["check_out", ">=", $check_in]
                    ])
                    ->orWhere([
                        ["check_in", "<=", $check_out],
                        ["check_out", ">=", $check_out]
                    ])
                    ->orWhere([
                        ["check_in", ">=", $check_in],
                        ["check_out", "<=", $check_out]
                    ])
            )
            ->where("person_number", $person_num);

            $matrimonial = (bool)($matrimonial);
            $single = (bool)($single);

            if($matrimonial || $single)
            {
                if($matrimonial)
                    $rooms->where("matrimonial_bed", ">", 0);
                else
                    $rooms->where("matrimonial_bed", 0);

                if($single)
                    $rooms->where("single_bed", ">", 0);
                else
                    $rooms->where("single_bed", 0);
            }

            if($min_fee !== null)
                $rooms->where("nightly_fee", ">=", $min_fee);

            if($max_fee !== null)
                $rooms->where("nightly_fee", "<=", $max_fee);

            $rooms = $rooms->get();
        }

        if( count($rooms) != 0 )
        {
            $json = array(
                "ok" => true,
                "results" => array()
            );

            foreach($rooms as $room)
            {
                $roomType= $room->room_type;
                $roomPhotos= $room->room_photo;

                $index = count($json["results"]);

                $json["results"][]= array(
                    "roomNumber" => $room->room_number,
                    "personNumber" => $room->person_number,
                    "matrimonialBed" => $room->matrimonial_bed,
                    "singleBed" => $room->single_bed,
                    "wifi" => (bool)($room->wifi),
                    "wifiFree" => (bool)($room->wifi_free),
                    "minibar" => (bool)($room->minibar),
                    "soundproofing" => (bool)($room->soundproofing),
                    "swimmingPool" => (bool)($room->swimming_pool),
                    "privateBathroom" => (bool)($room->private_bathroom),
                    "airConditioning" => (bool)($room->air_conditioning),
                    "sqm" => $room->sqm,
                    "nightlyFee" => $room->nightly_fee,
                    "description" => $room->description,
                    "roomType" => $roomType->type,
                    "accomodation" => $roomType->accomodation,
                    "photos" => []
                );

                if($roomPhotos !== null)
                {
                    foreach($roomPhotos as $photo)
                    {
                        $json["results"][$index]["photos"][] = $photo->photo_path;
                    }
                }
            }

            return $json;
        }
        else
            return array("ok" => false);
    }

    public function bookingRoom(Request $request)
    {
        $room_number = $request->room_number;
        $check_in = $request->check_in;
        $check_out = $request->check_out;
        $email = $request->email;

        if(!AuthController::checkAuth())
            return array("ok" => null);

        $validator = Validator::make(
            [
                "room_number" => $room_number,
                "check_in"  => $check_in,
                "check_out" => $check_out
            ],
            [
                "room_number" => ["required", "regex:/^\d{3}[a-zA-Z]$/"],
                "check_in"  => ["required", "date_format:Y-m-d"],
                "check_out" => ["required", "date_format:Y-m-d", "after:check_in"]
            ]
        );

        $validator->stopOnFirstFailure(); //Funziona

        if($validator->fails())
            return array("ok" => null);

        if(AuthController::isAdmin())
        {
            if($email === AuthController::getEmail())
                return array("ok" => null);

            $validateEmail= CheckController::checkEmail($email);

            if($validateEmail["ok"] !== true)
                return array("ok" => null);

            $person_id = Person::where("email", $email)->first()->id;
        }
        else
            $person_id = AuthController::getID();

        $room = Room::select("room_number", "nightly_fee")
                    ->where("room_number", $room_number)
                    ->first();

        if($room === null)
            return array("ok" => null);

        $nightly_fee = $room->nightly_fee;

        $isBooked = Rent::where("room_id", $room_number)
                    ->where(function($query) use($check_in, $check_out) {
                        $query->where([
                            ["check_in", "<=", $check_in],
                            ["check_out", ">=", $check_in]
                        ])
                        ->orWhere([
                            ["check_in", "<=", $check_out],
                            ["check_out", ">=", $check_out]
                        ])
                        ->orWhere([
                            ["check_in", ">=", $check_in],
                            ["check_out", "<=", $check_out]
                        ]);
                    })->first();

        if($isBooked !== null)
            return array("ok" => false);

        $origin = new \DateTime($check_in);
        $target = new \DateTime($check_out);
        $night_stay = ($origin->diff($target))->days;

        $rent = new Rent;
        $rent->person_id = $person_id;
        $rent->room_id = $room_number;
        $rent->night_stay = $night_stay;
        $rent->nightly_fee= $nightly_fee;
        $rent->check_in = $check_in;
        $rent->check_out = $check_out;
        $rent->save();

        return array("ok" => true);
    }
}

?>
