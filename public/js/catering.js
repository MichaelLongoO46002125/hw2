const menuList = document.querySelector('[data-subSec="menu-list"]');
const recipeDetails = document.querySelector('[data-subSec="recipe-details"]');
let res = null;

function closeRM(event)
{
    recipeDetails.classList.remove("show-flex");
}

function showRecipeDetails(ind)
{
    if(ind < 0 || ind > res.length)
    {
        console.log("Error: Index out of bounds, index: " + ind);
        return;
    }

    recipeDetails.querySelector("img").src= res[ind].image;
    recipeDetails.querySelector("h2").textContent= res[ind].title;

    let str= "";

    for(let i=0; i < res[ind].cuisines.length; i++)
    {
        if( i == 0 )
            str += res[ind].cuisines[i];
        else
            str += ", " + res[ind].cuisines[i];
    }

    recipeDetails.querySelector('[data-detail="cuisines"]').textContent = str;

    str= "";

    for(let i=0; i < res[ind].dishTypes.length; i++)
    {
        if( i == 0 )
            str += res[ind].dishTypes[i];
        else
            str += ", " + res[ind].dishTypes[i];
    }

    recipeDetails.querySelector('[data-detail="dish-types"]').textContent = str;

    recipeDetails.querySelector('[data-detail="vegan"]').textContent = res[ind].vegan? "Si" : "No";
    recipeDetails.querySelector('[data-detail="vegetarian"]').textContent = res[ind].vegetarian? "Si" : "No";

    if( res[ind].glutenFree )
        recipeDetails.querySelector('[data-detail="gluten"]').textContent= "Senza glutine";
    else
        recipeDetails.querySelector('[data-detail="gluten"]').textContent= "Contiene glutine";

    if( res[ind].dairyFree )
        recipeDetails.querySelector('[data-detail="dairy"]').textContent= "Senza latticini";
    else
        recipeDetails.querySelector('[data-detail="dairy"]').textContent= "Contiene latticini";

    str= "";
    const ingr = res[ind].extendedIngredients;

    for(let i=0; i<ingr.length; i++)
    {
        if(i==0)
            str+= ingr[i].nameClean;
        else
            str+= ", " + ingr[i].nameClean;
    }

    recipeDetails.querySelector('[data-detail="ingredients"]').textContent= str;
}

function showRecipe(event)
{
    showRecipeDetails( event.currentTarget.dataset.recipeInd );
    recipeDetails.classList.add("show-flex");
}

function onJSON(json)
{
    if( json !== null && json.ok)
    {
        res = json.recipes;

        for( let i=0; i<res.length; i++ )
        {
            const listItem = document.createElement("div");
            listItem.classList.add("menu-list-item");
            listItem.dataset.recipeInd= i;
            listItem.addEventListener('click', showRecipe);

            let elem = document.createElement("span");
            elem.textContent= res[i].title;
            listItem.appendChild(elem);

            elem = document.createElement("span");
            elem.textContent= res[i].pricePerServing;
            listItem.appendChild(elem);

            menuList.appendChild(listItem);
        }
    }
    else
        res= null;

    if(res !== null && res.length > 0)
        showRecipeDetails(0);
    else
    {
        menuList.querySelector("h4").classList.remove("hidden");
        recipeDetails.classList.add("hidden");
    }
}

function onResponse(response)
{
    if(response.ok)
        return response.json();
    else
        return null;
}

fetch(routeFetchCatering).then(onResponse).then(onJSON);

const btnCloseRM= document.querySelector('[data-btn="close-rm"]');
btnCloseRM.addEventListener('click', closeRM);
