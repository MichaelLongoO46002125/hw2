const logform= document.forms["login-form"];

function validateEmail(event)
{
    const field= logform.querySelector('[data-error="email"]');

    if(logform.email.value.trim().length == 0)
    {
        field.textContent= "Inserire una email!";
        field.classList.remove("hidden");
    }
    else if(!/^(([^<>()\[\]\\.,;:\s@"]+(\.[^<>()\[\]\\.,;:\s@"]+)*)|(".+"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/
            .test(logform.email.value.toLowerCase())
    )
    {
        field.textContent= "Inserire una email valida!";
        field.classList.remove("hidden");
    }
    else
        field.classList.add("hidden");
}

function validatePw(event)
{
    const field= logform.querySelector('[data-error="pw"]');

    if(logform.password.value.trim().length == 0)
    {
        field.textContent= "La password non può essere vuota!";
        field.classList.remove("hidden");
    }
    else
        field.classList.add("hidden");
}

function validateForm(event)
{
    event.preventDefault();

    if(logform.email.value.trim().length >0 && logform.password.value.trim().length > 0)
    {
        if(!/^(([^<>()\[\]\\.,;:\s@"]+(\.[^<>()\[\]\\.,;:\s@"]+)*)|(".+"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/
            .test(logform.email.value.toLowerCase())
        )
        {
            const field= logform.querySelector('[data-error="email"]');
            field.textContent= "Inserire una email valida!";
            field.classList.remove("hidden");
        }
        else
            logform.submit();
    }
    else
    {
        logform.querySelector('[data-error="general"]').classList.remove("hidden");
        logform.querySelector('[data-error="email"]').classList.add("hidden");
        logform.querySelector('[data-error="pw"]').classList.add("hidden");
    }
}

logform.addEventListener('submit', validateForm);
logform.email.addEventListener('blur', validateEmail);
logform.password.addEventListener('blur', validatePw);
