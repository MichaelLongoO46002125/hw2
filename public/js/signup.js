const regform= document.forms["signup-form"];

function validateName()
{
    if(regform.name.value.trim().length == 0)
    {
        regform.querySelector('[data-error="name"]').classList.remove("hidden");
        return false;
    }

    regform.querySelector('[data-error="name"]').classList.add("hidden");
    return true;
}

function validateLastName()
{
    if(regform.last_name.value.trim().length == 0)
    {
        regform.querySelector('[data-error="last_name"]').classList.remove("hidden");
        return false;
    }

    regform.querySelector('[data-error="last_name"]').classList.add("hidden");
    return true;
}

function validateTel()
{
    if(!/^[+]\d{1,15}$/.test(regform.tel.value))
    {
        regform.querySelector('[data-error="tel"]').classList.remove("hidden");
        return false;
    }

    regform.querySelector('[data-error="tel"]').classList.add("hidden");
    return true;
}

function validatePw()
{
    const spanMsg= regform.querySelector('[data-error="pw"]');
    let errorMsg = "";
    let ok= true;

    validateCPw();

    if(regform.pw.value.trim().length < 8)
    {
        errorMsg = errorMsg + "La password deve contenere almeno 8 caratteri!\nNon contano gli spazi all'inizio e alla fine.";
        ok= false;
    }

    if(!/[A-Z]/.test(regform.pw.value) || !/[a-z]/.test(regform.pw.value) || !/[0-9]/.test(regform.pw.value))
    {
        if(ok)
        {
            errorMsg = errorMsg + "La password deve contenere almeno 1 lettera maiuscola, almeno 1 lettera minuscola e almeno 1 numero!";
            ok= false;
        }
        else
        {
            errorMsg = errorMsg + "\nLa password deve contenere almeno 1 lettera maiuscola, almeno 1 lettera minuscola e almeno 1 numero!";
        }
    }

    if(ok)
    {
        spanMsg.textContent = "Default"; //In questo modo lo span avrà un'altezza
        spanMsg.classList.add("hidden");
    }
    else
    {   spanMsg.textContent = errorMsg;
        spanMsg.classList.remove("hidden");
    }

    return ok;
}

function validateCPw()
{
    if(regform.pw.value != regform.cpw.value)
    {
        regform.querySelector('[data-error="cpw"]').classList.remove("hidden");
        return false;
    }

    regform.querySelector('[data-error="cpw"]').classList.add("hidden");
    return true;
}

function onEventJSON(json)
{
    if(json === null)
        return;

    const errorMsg = regform.querySelector('[data-error="email"]');

    if(json.ok)
    {
        errorMsg.textContent = "Email già in uso!";
        errorMsg.classList.remove("hidden");
        return;
    }

    errorMsg.classList.add("hidden");
}

function onFormJSON(json)
{
    if(json === null)
        return;

    const errorMsg = regform.querySelector('[data-error="email"]');

    if(json.ok)
    {
        errorMsg.textContent = "Email già in uso!";
        errorMsg.classList.remove("hidden");
        return;
    }

    regform.submit();
}

function onResponse(response)
{
    if(response.ok)
        return response.json();
    else
        return null;
}

function controlEmail()
{
    const errorMsg = regform.querySelector('[data-error="email"]');

    if(regform.email.value.trim().length == 0)
    {
        errorMsg.textContent = "L'email non può essere vuota!";
        errorMsg.classList.remove("hidden");
        return false;
    }
    else if(!/^(([^<>()\[\]\\.,;:\s@"]+(\.[^<>()\[\]\\.,;:\s@"]+)*)|(".+"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/
             .test(regform.email.value.toLowerCase())
    )
    {
        errorMsg.textContent = "Email non valida!";
        errorMsg.classList.remove("hidden");
        return false;
    }

    return true;
}

function eventValidateEmail()
{
    if(controlEmail())
    {
        fetch(routeCheckEmail + "/" + encodeURIComponent(regform.email.value.toLowerCase())).then(onResponse).then(onEventJSON);
    }
}

function validateEmail()
{
    if(controlEmail())
    {
        fetch(routeCheckEmail + "/" + encodeURIComponent(regform.email.value.toLowerCase())).then(onResponse).then(onFormJSON);
    }
}

function validateForm(event)
{
    event.preventDefault();

    if( regform.name.value.trim().length == 0 || regform.last_name.value.trim().length == 0 || regform.email.value.trim().length == 0
        || regform.tel.value.trim().length == 0 || regform.pw.value.trim().length == 0 || regform.cpw.value.trim().length == 0
    )
    {
        regform.querySelector('[data-error="general"]').classList.remove("hidden");
    }
    else
    {
        if(validateName() && validateLastName() && validateTel() && validatePw() && validateCPw())
        {
            validateEmail();
        }
    }
}

regform.addEventListener('submit', validateForm);
regform.name.addEventListener('blur', validateName);
regform.last_name.addEventListener('blur', validateLastName);
regform.email.addEventListener('blur', eventValidateEmail);
regform.tel.addEventListener('blur', validateTel);
regform.pw.addEventListener('blur', validatePw);
regform.cpw.addEventListener('keyup', validateCPw);
