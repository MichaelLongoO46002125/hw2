<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Http;
use Illuminate\Routing\Controller as BaseController;

class GalleryController extends BaseController
{
    public function gallery()
    {
        return view("gallery")->with("authStatus", AuthController::authStatus());
    }

    public function fetchGallery()
    {
        $results = Http::acceptJson()->get(config("hw.api.unsplash.endpoint"), [
            "client_id" => config("hw.api.unsplash.apikey"),
            "query" => "Hotel room",
            "per_page" => 30,
            "orientation" => "landscape"
        ]);

        if($results->ok())
        {
            $results= $results->json();
            $response = array(
                "ok" => true,
                "photos" => []
            );

            foreach($results["results"] as $result)
            {
                $response["photos"][]= array(
                    "image" => $result["urls"]["regular"],
                    "description" => $result["description"]
                );
            }

            return $response;
        }
        else
        {
            return array("ok" => false);
        }
    }
}

?>
