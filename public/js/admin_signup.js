const regform= document.forms["signup-form"];
const subForm= document.querySelector('[data-subform="subform"]');

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

    if( regform.name.value.trim().length == 0 || regform.last_name.value.trim().length == 0 || regform.email.value.trim().length == 0 ||
        regform.tel.value.trim().length == 0 || (
            regform.job.value !== "USER" && (
                    regform.salary.value.trim().length == 0 ||
                    regform.duty_start.value.trim().length == 0 ||
                    regform.duty_end.value.trim().length == 0
            )
        )
    )
    {
        regform.querySelector('[data-error="general"]').classList.remove("hidden");
    }
    else
    {
        if( validateName() && validateLastName() && validateTel() )
        {
            if(  regform.job.value === "USER" ||
                ( regform.job.value !== "USER" && validateDutyStart() && validateDutyEnd() && validateSalary() )
            )
                validateEmail();
        }
    }
}

function selectChanged()
{
    if(regform.job.value !== "USER")
        subForm.classList.remove("none");
    else
        subForm.classList.add("none");
}

function validateSalary()
{
    if(!/(^(0|0.00)$)|(^[1-9]\d*(\.\d{1,2})?$)/.test(regform.salary.value))
    {
        regform.querySelector('[data-error="salary"]').classList.remove("hidden");
        return false;
    }

    regform.querySelector('[data-error="salary"]').classList.add("hidden");
    return true;
}

function validateDuty(duty)
{
    if(!/^(([0-1][0-9])|(2[0-3])):[0-5][0-9]$/.test(duty))
        return false;

    return true;
}

function validateDutyStart()
{
    if(!validateDuty(regform.duty_start.value))
    {
        regform.querySelector('[data-error="duty_start"]').classList.remove("hidden");
        return false;
    }

    regform.querySelector('[data-error="duty_start"]').classList.add("hidden");
    return true;
}

function validateDutyEnd()
{
    if(!validateDuty(regform.duty_end.value))
    {
        regform.querySelector('[data-error="duty_end"]').classList.remove("hidden");
        return false;
    }

    regform.querySelector('[data-error="duty_end"]').classList.add("hidden");
    return true;
}

regform.addEventListener('submit', validateForm);
regform.name.addEventListener('blur', validateName);
regform.last_name.addEventListener('blur', validateLastName);
regform.email.addEventListener('blur', eventValidateEmail);
regform.tel.addEventListener('blur', validateTel);
regform.job.addEventListener('change', selectChanged);
regform.salary.addEventListener('blur', validateSalary);
regform.duty_start.addEventListener('blur', validateDutyStart);
regform.duty_end.addEventListener('blur', validateDutyEnd);
