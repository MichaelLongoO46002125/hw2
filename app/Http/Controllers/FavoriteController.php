<?php

namespace App\Http\Controllers;

use Illuminate\Routing\Controller as BaseController;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;
use App\Models\Person;

class FavoriteController extends BaseController
{
    public function fetchFavorites($num = 3, $off = 0)
    {
        if(!AuthController::checkAuth())
            return array("ok" => null);

        $validator = Validator::make(
            [
                "num" => $num,
                "off" => $off
            ],
            [
                "num" => ["nullable", "integer", "min:1"],
                "off" => ["nullable", "integer", "min:0"]
            ]
        );

        if($validator->fails())
            return array("ok" => null);

        $favorites = Person::with([
            'content' => function ($query) use($num, $off) {
                $query->limit($num)
                    ->offset($off)
                    ->select("date", "id", "image_url", "title");
            },
            'content.tag:name'
        ])
        ->where("id", AuthController::getID())
        ->get(["id"]);

        if(count($favorites) > 0)
            $favorites = $favorites[0]["content"];
        else
            return array("ok" => null);

        if(count($favorites) > 0)
        {
            $json= array(
                "ok" => true,
                "favorites" => array()
            );

            foreach($favorites as $favorite)
            {
                $tags = [];

                foreach($favorite->tag as $tag)
                    $tags[] = $tag->name;

                $json["favorites"][] = [
                    "id" => $favorite->id,
                    "title" => $favorite->title,
                    "image" => $favorite->image_url,
                    "date"  => date("d/m/Y", strtotime($favorite->date)),
                    "tags" => $tags
                ];
            }

            return $json;
        }
        else
            return array("ok" => false);
    }

    public function addFavorite(Request $request)
    {
        if(!AuthController::checkAuth())
            return array("ok" => null);

        $id= $request->id;

        $validator = Validator::make(
            ["id" => $id],
            ["id" => ["required", "integer", "min:1"]]
        );

        if($validator->fails())
            return array("ok" => null);

        $person = Person::find(AuthController::getID());

        if($person === null)
            return array("ok" => null);

        try
        {
            $person->content()->attach($id);

            return array("ok" => true, "id" => $id);
        }
        catch (\Exception $e)
        {
            return array("ok" => false);
        }
    }


    public function removeFavorite(Request $request)
    {
        if(!AuthController::checkAuth())
            return array("ok" => null);

        $id= $request->id;

        $validator = Validator::make(
            ["id" => $id],
            ["id" => ["required", "integer", "min:1"]]
        );

        if($validator->fails())
            return array("ok" => null);

        $person = Person::find(AuthController::getID());

        if($person === null)
            return array("ok" => null);

        if($person->content()->detach($id))
            return array("ok" => true, "id" => $id);
        else
            return array("ok" => false);
    }

}

?>
