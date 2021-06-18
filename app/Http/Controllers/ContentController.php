<?php

namespace App\Http\Controllers;

use Illuminate\Routing\Controller as BaseController;
use Illuminate\Support\Facades\Validator;
use App\Models\Content;

class ContentController extends BaseController
{
    public function fetchContents($num = 4, $off = 0, $title = null)
    {
        if($title)
            $title= trim($title);

        $validator = Validator::make(
            [
                "num" => $num,
                "off" => $off,
                "title" => $title
            ],
            [
                "num" => ["nullable", "integer", "min:1"],
                "off" => ["nullable", "integer", "min:0"],
                "title" => ["nullable", "string"]
            ]
        );

        if($validator->fails())
            return array("ok" => null);

        $authenticated = AuthController::checkAuth();

        if($authenticated)
        {
            $contents = Content::with(
                [
                    'tag:name',
                    "person" => function($query) { $query->where("id", AuthController::getID())->get(["id"]); }
                ]
            );
        }
        else
            $contents = Content::with('tag:name');

        if($title)
            $contents = $contents->where("title", "LIKE", "%$title%");

        $contents= $contents->orderBy("date", "desc")
                    ->offset($off)
                    ->limit($num)
                    ->get();

        if(count($contents) > 0)
        {
            $json= array(
                "ok" => true,
                "contents" => array()
            );

            foreach($contents as $content)
            {
                $tags = [];

                foreach($content->tag as $tag)
                    $tags[] = $tag->name;

                if($authenticated)
                {
                    if(count($content->person) > 0)
                        $isFav = true;
                    else
                        $isFav = false;
                }
                else
                    $isFav = null;

                $json["contents"][] = [
                    "id" => $content->id,
                    "title" => $content->title,
                    "image" => $content->image_url,
                    "date"  => date("d/m/Y", strtotime($content->date)),
                    "description" => $content->description,
                    "tags" => $tags,
                    "isFav" => $isFav
                ];
            }

            return $json;
        }
        else
            return array("ok" => false);
    }
}

?>
