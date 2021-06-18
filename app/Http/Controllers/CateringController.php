<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Http;
use Illuminate\Routing\Controller as BaseController;

class CateringController extends BaseController
{
    public function catering()
    {
        return view("catering")->with("authStatus", AuthController::authStatus());
    }

    public function fetchCatering()
    {
        $results = Http::acceptJson()->get(config("hw.api.spoonacular.endpoint"), [
            "apiKey" => config("hw.api.spoonacular.apikey"),
            "cuisine" => "Italian,America,European",
            "addRecipeInformation" => "true",
            "fillIngredients" => "true",
            "number" => 15
        ]);

        if($results->ok())
        {
            $results= $results->json();
            $response = array(
                "ok" => true,
                "recipes" => []
            );

            foreach($results["results"] as $result)
            {
                $response["recipes"][] = array(
                    "title" => $result["title"],
                    "pricePerServing" => $result["pricePerServing"],
                    "image" => $result["image"],
                    "cuisines" => $result["cuisines"],
                    "dishTypes" => $result["dishTypes"],
                    "vegan" => $result["vegan"],
                    "vegetarian" => $result["vegetarian"],
                    "glutenFree" => $result["glutenFree"],
                    "dairyFree" => $result["dairyFree"],
                    "extendedIngredients" => $result["extendedIngredients"]
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
