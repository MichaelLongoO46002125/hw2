const contentSec = document.querySelector('section[data-sec="content-sec"]');
const favSec= document.querySelector('section[data-sec="fav-sec"]');
const favList= favSec.querySelector('.fav-list');
const contentMsg= document.querySelector('[data-cont-msg="msg"]');
const contPrev= document.querySelector('[data-btn="cont-prev"]');
const contNext= document.querySelector('[data-btn="cont-next"]');
const favPrev= document.querySelector('[data-btn="fav-prev"]');
const favNext= document.querySelector('[data-btn="fav-next"]');
const searchBar= document.forms["search-form"].search_bar;
const contentPageSize=4;
const favPageSize=3;

let contents = null;
let viewableContent = [];
let contentsOffset = 0;
let otherCont= true;

let favorites = null;
let viewableFav = [];
let favOffset = 0;
let otherFav= true;

let searchTitle= "";

//Funzioni e Listener

function searchByID(ID) {

    if( (typeof ID) === "string" )
        ID= parseInt(ID,10);

    for(let cont of contents)
    {
        if(cont.id === ID)
            return cont;
    }

    return null;
}

function favoriteButtonManager()
{
    if(favOffset == 0)
        favPrev.classList.add("hidden");
    else
        favPrev.classList.remove("hidden");

    if(
        (favOffset >= viewableFav.length-favPageSize && otherFav) ||
        (favOffset < viewableFav.length-favPageSize)
    )
        favNext.classList.remove("hidden");
    else if( favOffset >= viewableFav.length-favPageSize )
        favNext.classList.add("hidden");
}

function contentButtonManager()
{
    if(contentsOffset == 0)
        contPrev.classList.add("hidden");
    else
        contPrev.classList.remove("hidden");

    if(
        (contentsOffset >= viewableContent.length-contentPageSize && otherCont) ||
        (contentsOffset < viewableContent.length-contentPageSize)
    )
        contNext.classList.remove("hidden");
    else if( contentsOffset >= viewableContent.length-contentPageSize )
        contNext.classList.add("hidden");
}

function removeContent(cont)
{
    const tmp= document.querySelector('[data-content-id="' + cont.id + '"]');
    if(tmp !== null)
        tmp.remove();
}

function removeFavorite(id)
{
    const i=viewableFav.indexOf(id);

    if(i === -1)
        return;

    const fav= favSec.querySelector('[data-fav-id="' + id +'"]');

    if(fav !== null)
    {
        let max= favOffset+3;
        let inPage= false;
        if(favOffset+2 > viewableFav.length-1)
        {
            max= viewableFav.length;
        }

        for(let off= favOffset; off<max; off++)
        {
            if(viewableFav[off] == id)
                inPage= true;
        }

        fav.remove();

        if(viewableFav.length > favPageSize && inPage)
        {
            if(favOffset>0)
            {
                favList.querySelector('[data-fav-id="' + viewableFav[--favOffset] + '"]')
                    .classList.remove('hidden');
            }
            else
            {
                favList.querySelector('[data-fav-id="' + viewableFav[favOffset+favPageSize] + '"]')
                    .classList.remove('hidden');
            }
        }

        viewableFav.splice(i, 1);
        favorites.splice(i, 1);

        if( viewableFav.length > 0 && viewableFav.length < favPageSize && otherFav)
        {
            fetch(routeFetchFavorites + "/num/1/offset/" + viewableFav.length).then(onResponse).then(onLoadFavoriteJSON);
        }
        else if(viewableFav.length === 0)
        {
            favSec.classList.add('hidden');
        }
    }
}

function resetContents()
{
    if(contents !== null)
    {
        while(contents.length !== 0)
        {
            removeContent(contents[0]);
            contents.shift();
        }
        contents = null;
        viewableContent= [];
        contentsOffset= 0;
    }
}

function createContent(cont)
{
    const divContent= document.createElement('div');
    divContent.classList.add('content-col');
    if(viewableContent.length >= contentPageSize)
        divContent.classList.add('hidden');
    divContent.dataset.contentId= cont.id;
    contentSec.appendChild(divContent);

    let elem= document.createElement('img');
    elem.src= cont.image;
    divContent.appendChild(elem);

    const divContentText= document.createElement('div');
    divContentText.classList.add('content-text');
    divContent.appendChild(divContentText);

    const divTagDate= document.createElement('div');
    divContentText.appendChild(divTagDate);

    elem= document.createElement('span');
    elem.classList.add('content-tag');
    let tags= "";

    for(let i=0; i<cont.tags.length; i++)
    {
        if(i==0)
            tags= cont.tags[i];
        else
            tags+=", " + cont.tags[i];
    }

    elem.textContent= tags;
    divTagDate.appendChild(elem);

    elem= document.createElement('span');
    elem.textContent= cont.date;
    divTagDate.appendChild(elem);

    elem= document.createElement('h4');
    elem.textContent= cont.title;
    divContentText.appendChild(elem);

    elem= document.createElement('p');
    elem.textContent= 'Clicca per mostrare la descrizione...';
    elem.addEventListener('click', showDescription);
    divContentText.appendChild(elem);

    elem= document.createElement('div');

    if(cont.isFav !== null)
    {
        if(cont.isFav == true)
        {
            elem.classList.add('btn-rem-fav');
            elem.addEventListener('click', remFav);
        }
        else
        {
            elem.classList.add('btn-add-fav');
            elem.addEventListener('click', addFav);
        }
        divContent.appendChild(elem);
    }
    viewableContent.push(cont.id);
}

function createFavorite(fav)
{
    if(favSec.classList.contains('hidden'))
        favSec.classList.remove('hidden');

    const favItem = document.createElement('div');
    favItem.classList.add('fav-item');
    if(viewableFav.length >= favPageSize)
        favItem.classList.add('hidden');
    favItem.dataset.favId= fav.id;

    let elem = document.createElement('img');
    elem.src= fav.image; //<img src="XXXX">
    favItem.appendChild(elem);

    const divTagDate= document.createElement('div');
    favItem.appendChild(divTagDate);

    elem= document.createElement('span');
    elem.classList.add('content-tag');
    let tags= "";

    for(let i=0; i<fav.tags.length; i++)
    {
        if(i==0)
            tags= fav.tags[i];
        else
            tags+=", " + fav.tags[i];
    }

    elem.textContent= tags;
    divTagDate.appendChild(elem); //<span class="content-tag">TAG</span>

    elem= document.createElement('span');
    elem.textContent= fav.date;
    divTagDate.appendChild(elem); //<span>DD/MM/YYYY</span>

    elem= document.createElement('h4');
    elem.textContent= fav.title;
    favItem.appendChild(elem); //<h4>Titolo</h4>

    elem= document.createElement('div');
    elem.classList.add('btn-rem-fav');
    elem.addEventListener('click', remFav);
    favItem.appendChild(elem); //<div class="btn-rem-fav"></div>

    favList.appendChild(favItem); //<div class="fav-item" data-fav-id="XXXX"></div>
    viewableFav.push(fav.id);
}

function createFavoriteFromContent(cont)
{
    const fav = {
        "date": cont.date,
        "id": cont.id,
        "image": cont.image,
        "tags": cont.tags,
        "title": cont.title
    };
    if(favorites === null)
        favorites = [];
    favorites.push(fav);
}

//Listener per la rimozione/aggiunta dei preferiti

function onRemFavJSON(json)
{   console.log(json);
    if(json === null || !json.ok)
        return;

    let cont = contentSec.querySelector('[data-content-id="' + json.id +'"]');

    if(cont !== null)
    {
        cont= cont.querySelector('.btn-rem-fav');
        cont.removeEventListener('click', remFav);

        cont.classList.add('btn-add-fav');
        cont.classList.remove('btn-rem-fav');

        cont.addEventListener('click', addFav);
    }

    removeFavorite(json.id);
}

function remFav(event)
{
    if(event.currentTarget.parentNode.dataset.favId !== undefined)
    {
        const id= parseInt(event.currentTarget.parentNode.dataset.favId, 10);
        const i=viewableFav.indexOf(id);

        if(i === -1)
            return;

        const data = {
            _token: csrf_token,
            id: id,
        };

        const options={
            method: "POST",
            body: JSON.stringify(data),
            headers: {
                'Content-Type': 'application/json'
            }
        };
        fetch(routeRemoveFavorite, options).then(onResponse).then(onRemFavJSON).then(favoriteButtonManager);
    }
    else
    {
        const id= parseInt(event.currentTarget.parentNode.dataset.contentId);
        const cont= searchByID(id);

        if( cont === null)
            return;

        const data = {
            _token: csrf_token,
            id: id,
        };

        const options={
            method: "POST",
            body: JSON.stringify(data),
            headers: {
                'Content-Type': 'application/json'
            }
        };
        fetch(routeRemoveFavorite, options).then(onResponse).then(onRemFavJSON).then(favoriteButtonManager);
    }
}

function onAddFavJSON(json)
{
    if(json === null || !json.ok)
        return;

    const cont= searchByID(json.id);

    if(cont === null)
        return;

    createFavorite(cont);
    createFavoriteFromContent(cont);

    let contBtn = contentSec.querySelector('[data-content-id="' + json.id +'"]');

    if(contBtn !== null)
    {
        contBtn= contBtn.querySelector('.btn-add-fav');
        contBtn.removeEventListener('click', addFav);

        contBtn.classList.remove('btn-add-fav');
        contBtn.classList.add('btn-rem-fav');

        contBtn.addEventListener('click', remFav);
    }
}

function addFav(event)
{
    const id= event.currentTarget.parentNode.dataset.contentId;
    const cont= searchByID(id);

    if( cont === null)
        return;

    const data = {
        _token: csrf_token,
        id: id,
    };

    const options={
        method: "POST",
        body: JSON.stringify(data),
        headers: {
            'Content-Type': 'application/json'
        }
    };

    fetch(routeAddFavorite, options).then(onResponse).then(onAddFavJSON).then(favoriteButtonManager);
}

//Listener per i pulsanti prossimo/precedente preferito
function showNextFav(res)
{
    if(res === null)
       return;

    if(favOffset < viewableFav.length-favPageSize)
    {
        favList.querySelector('[data-fav-id="' + viewableFav[favOffset] + '"]').classList.add('hidden');
        favList.querySelector('[data-fav-id="' + viewableFav[favOffset+favPageSize] + '"]').classList.remove('hidden');

        favOffset++;
    }
}

function onNextFav(){
    if(favOffset >= viewableFav.length-favPageSize && otherFav)
    {
        fetch(routeFetchFavorites + "/num/3/offset/" + favorites.length)
            .then(onResponse).then(onLoadFavoriteJSON).then(showNextFav).then(favoriteButtonManager);
    }
    else
    {
        showNextFav(true);
        favoriteButtonManager();
    }
}

function onPrevFav(){
    if(favOffset>0)
    {
        favOffset--;
        favList.querySelector('[data-fav-id="' + viewableFav[favOffset+favPageSize] + '"]').classList.add('hidden');
        favList.querySelector('[data-fav-id="' + viewableFav[favOffset] + '"]').classList.remove('hidden');
        favoriteButtonManager();
    }
}

//Listener per i pulsanti prossimo/precedente contenuto
function showNextCont(res)
{
    if(res === null)
        return;

    if(contentsOffset < viewableContent.length-contentPageSize)
    {
        contentSec.querySelector('[data-content-id="' + viewableContent[contentsOffset] + '"]')
            .classList.add('hidden');

        contentSec.querySelector('[data-content-id="' + viewableContent[contentsOffset+contentPageSize] + '"]')
            .classList.remove('hidden');

        contentsOffset++;
    }
}

function onNextCont(){
    if(contentsOffset >= viewableContent.length-contentPageSize && otherCont)
    {
        if(searchTitle != "")
            fetch(routeFetchContents + "/num/" + contentPageSize + "/offset/" + contents.length + "/title/" + encodeURIComponent(searchTitle))
                .then(onResponse).then(onLoadContentJSON).then(showNextCont).then(contentButtonManager);
        else
            fetch(routeFetchContents + "/num/" + contentPageSize + "/offset/" + contents.length)
                .then(onResponse).then(onLoadContentJSON).then(showNextCont).then(contentButtonManager);
    }
    else
    {
        showNextCont(true);
        contentButtonManager();
    }
}

function onPrevCont(){
    if(contentsOffset>0)
    {
        contentsOffset--;

        contentSec.querySelector('[data-content-id="' + viewableContent[contentsOffset+contentPageSize] + '"]')
            .classList.add('hidden');

        contentSec.querySelector('[data-content-id="' + viewableContent[contentsOffset] + '"]')
            .classList.remove('hidden');

        contentButtonManager();
    }
}

//Listener per le descrizioni
function showDescription(event)
{
    const cont= searchByID(event.currentTarget.parentNode.parentNode.dataset.contentId);

    if(cont === null)
    {
        console.log("Errore");
        return;
    }

    event.currentTarget.textContent= cont.description;

    event.currentTarget.removeEventListener('click', showDescription);
    event.currentTarget.addEventListener('click', hideDescription);
}

function hideDescription(event)
{
    event.currentTarget.textContent= 'Clicca per mostrare la descrizione...';

    event.currentTarget.removeEventListener('click', hideDescription);
    event.currentTarget.addEventListener('click', showDescription);
}

//Listener per la barra di ricerca
function searchContents(event)
{
    event.preventDefault();
    const search= (searchBar.value.trim()).toUpperCase();

    if( search == "" && (search != searchTitle) )
    {
        contentMsg.classList.add("hidden");
        otherCont= true;
        resetContents();
        searchTitle= "";
        fetch(routeFetchContents).then(onResponse).then(onLoadContentJSON).then(contentButtonManager);
    }
    else if(search != searchTitle)
    {
        contentMsg.classList.add("hidden");
        otherCont= true;
        resetContents();
        searchTitle= search;

        fetch(routeFetchContents + "/num/" + contentPageSize + "/offset/0/title/" + encodeURIComponent(searchTitle))
            .then(onResponse).then(onLoadContentJSON).then(contentButtonManager);
    }
}

function onLoadContentJSON(json)
{   console.log(json);
    if( json === null )
    {
        if(contents === null)
            contentMsg.classList.remove('hidden');

        return null;
    }

    if(json.ok === false)
    {
        if(contents === null)
            contentMsg.classList.remove('hidden');

        otherCont= false;
        return false;
    }

    if(json.contents.length<contentPageSize)
        otherCont = false;

    if( contents !== null )
    {
        for(let cont of json.contents)
        {
            if(searchByID(cont.id) === null)
            {
                contents.push(cont);
                createContent(cont);
            }
        }
    }
    else
    {
        contents= json.contents;

        for(let cont of contents)
        {
            createContent(cont);
        }
    }

    return true;
}



function onLoadFavoriteJSON(json)
{
    if( json === null || json.ok === null )
        return null;

    if(!json.ok)
    {
        if(favorites == null)
            favSec.classList.add('hidden');
        otherFav= false;
        return false;
    }

    if(json.favorites.length<favPageSize)
        otherFav = false;

    if( favorites !== null )
    {
        for(let fav of json.favorites)
        {
            if(viewableFav.indexOf(fav.id) === -1)
            {
                favorites.push(fav);
                createFavorite(fav);
            }
        }
    }
    else
    {
        favorites= json.favorites;
        for(let fav of favorites)
            createFavorite(fav);
    }

    return true;
}

function onResponse(response)
{
    if(response.ok)
        return response.json();
    else
        return null;
}

//MAIN

fetch(routeFetchContents).then(onResponse).then(onLoadContentJSON).then(contentButtonManager);
fetch(routeFetchFavorites).then(onResponse).then(onLoadFavoriteJSON).then(favoriteButtonManager);

document.forms["search-form"].addEventListener('submit', searchContents);

contNext.addEventListener('click', onNextCont);
document.querySelector('[data-btn="cont-prev"]').addEventListener('click', onPrevCont);

favNext.addEventListener('click', onNextFav);
document.querySelector('[data-btn="fav-prev"]').addEventListener('click', onPrevFav);
