const gallerySec= document.querySelector('[data-sec="gallery-sec"]');
const modalSec= document.querySelector('[data-sec="modal-sec"]');
let results= null;

function closeModal(event)
{
    modalSec.classList.add("hidden");
    modalSec.innerHTML='';
    document.body.classList.remove("no-scroll");
}

function openModal(event)
{
    const ind= event.currentTarget.dataset.resIND;
    if( ind < 0 || ind >= results.length )
        return;

    modalSec.style.top= window.pageYOffset + "px";
    modalSec.classList.remove("hidden");
    document.body.classList.add("no-scroll");

    let elem= document.createElement("img");
    elem.src= results[ind].image;
    modalSec.appendChild(elem);

    elem= document.createElement("p");
    elem.textContent= results[ind].description;
    modalSec.appendChild(elem);
}

function onJson(json)
{
    if(json === null || !json.ok)
    {
        gallerySec.querySelector("h1").classList.remove("hidden");
        return;
    }

    results= json.photos;

    for(let i=0; i<results.length; i++)
    {
        const cont= document.createElement("div");
        const contImg= document.createElement("div");
        cont.appendChild(contImg);

        let elem= document.createElement("img");
        elem.src= results[i].image;
        contImg.appendChild(elem);

        elem= document.createElement("p");
        if( results[i].description !== null )
        {
            if(results[i].description.length > 100)
            {
                elem.textContent = results[i].description.substring(0,97) + "...";
                const textBtn= document.createElement("span");
                textBtn.textContent= "Leggi tutto";
                textBtn.addEventListener("click", openModal);
                textBtn.dataset.resIND= i;
                elem.appendChild(textBtn);
            }
            else
                elem.textContent = results[i].description;
        }
        else
            elem.textContent= "Descrizione non disponibile";
        cont.appendChild(elem);

        gallerySec.appendChild(cont);
    }
}

function onResponse(response)
{
    if(response.ok)
        return response.json();
    else
        return null;
}


fetch(routeFetchGallery).then(onResponse).then(onJson);

modalSec.addEventListener("click", closeModal);
