const modalBooking= document.querySelector('[data-modal="booking"]');
const msgError= modalBooking.querySelector('[data-modal-msg="error"]');
const emailError= modalBooking.querySelector('[data-modal-msg="email_error"]');
const email= modalBooking.querySelector('[data-modal-in="email"]');
const emailForm= document.forms["reg-email-form"];
let sendReq=false;

function closeModalBooking()
{
    modalBooking.classList.add("hidden");
    document.body.classList.remove("no-scroll");
}

function openModalBooking(event)
{
    msgError.classList.add("hidden");
    modalBooking.classList.remove("hidden");
    modalBooking.style.top= window.pageYOffset + "px";
    document.body.classList.add("no-scroll");
    let room = null;

    for(res of results)
    {
        if(res.roomNumber === event.currentTarget.dataset.roomNumber)
        {
            room= res;
            break;
        }
    }

    if( room !== null )
    {
        const mymodal = modalBooking.querySelector("div");
        mymodal.querySelector("h3").textContent = room.roomType + " " + room.accomodation;
        mymodal.querySelector("span").textContent = "Tariffa per notte: " + room.nightlyFee + "€";
        mymodal.querySelector('[data-modal-in="check_in"]').value= searchForm.check_in.value;
        mymodal.querySelector('[data-modal-in="check_out"]').value= searchForm.check_out.value;
        mymodal.querySelector('[data-modal-in="close"]').addEventListener("click", closeModalBooking);
        mymodal.querySelector('[data-modal-in="submit"]').dataset.roomNumber = room.roomNumber;
        mymodal.querySelector('[data-modal-in="submit"]').addEventListener("click", checkModalBooking);
    }
    else
    {
        closeModalBooking();
        openModalError("Si è verificato un errore!");
    }
}

function checkModalBooking(event)
{
    msgError.classList.add("hidden");

    let error= "";
    const today= new Date();
    const strToday = today.getFullYear() + "-" +
                    ((today.getMonth()+1) < 10 ? ("0" + (today.getMonth()+1)) : (today.getMonth()+1) )
                    + "-" + today.getDate();

    const checkIn = modalBooking.querySelector('[data-modal-in="check_in"]').value;
    const checkOut = modalBooking.querySelector('[data-modal-in="check_out"]').value;

    if(checkIn < strToday)
        error= "Data di check-in non valida!";

    if(checkIn >= checkOut)
        error+= (error !== "" ? "\n" : "") + "La data di check-out deve essere maggiore di quella di check-in!";

    if(error !== "")
    {
        msgError.textContent = error;
        msgError.classList.remove("hidden");
    }
    else
    {
        sendReq= event.currentTarget.dataset.roomNumber;
        checkEmail();
    }
}

function onBookingJSON(json)
{
    if( json === null || json.ok === null)
    {
        msgError.textContent = "Si è verificato un errore!";
        msgError.classList.remove("hidden");
    }
    else if( json.ok === false )
    {
        msgError.textContent = "La camera non è disponibile per il periodo selezionato!";
        msgError.classList.remove("hidden");
    }
    else
    {
        closeModalBooking();
        openModalMsg("Camera prenotata con successo!");
    }

}

function onBookingResponse(response)
{
    if(response.ok)
        return response.json();
    else
        return null;
}

function onEmailJSON(json)
{
    if(json === null)
    {
        sendReq=false;
        return;
    }

    if(!json.ok)
    {
        emailError.textContent = "Email non registrata!";
        emailError.classList.remove("hidden");
        sendReq=false;
        emailForm.email.value= email.value.toLowerCase();
        emailForm.classList.remove("hidden");
        return;
    }
    else
    {
        emailError.classList.add("hidden");
        if(sendReq)
        {
            const checkIn = modalBooking.querySelector('[data-modal-in="check_in"]').value;
            const checkOut = modalBooking.querySelector('[data-modal-in="check_out"]').value;

            const data = {
                _token: csrf_token,
                room_number: sendReq,
                check_in: checkIn,
                check_out: checkOut,
                email: email.value.toLowerCase()
            };

            const options={
                method: "POST",
                body: JSON.stringify(data),
                headers: {
                    'Content-Type': 'application/json'
                }
            };

            fetch(routeBookingRoom, options).then(onBookingResponse).then(onBookingJSON);

            sendReq=false;
        }
    }
}

function checkEmail()
{
    emailForm.classList.add("hidden");
    if(email.value.trim().length == 0)
    {
        emailError.textContent = "L'email non può essere vuota!";
        emailError.classList.remove("hidden");
        sendReq=false;
        return;
    }
    else if(!/^(([^<>()\[\]\\.,;:\s@"]+(\.[^<>()\[\]\\.,;:\s@"]+)*)|(".+"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/
             .test(email.value.toLowerCase())
    )
    {
        emailError.textContent = "Email non valida!";
        emailError.classList.remove("hidden");
        sendReq=false;
        return;
    }

    fetch(routeCheckEmail + "/" + encodeURIComponent(email.value.toLowerCase())).then(onBookingResponse).then(onEmailJSON);
}

email.addEventListener("blur", checkEmail);
