const btnNavMenu= document.querySelector('#nav-menu');

function closeNavMenu()
{
    btnNavMenu.addEventListener('click', openNavMenu);
    btnNavMenu.removeEventListener('click', closeNavMenu);

    document.querySelector('#nav-links').classList.remove('open-nav-menu');
}

function openNavMenu()
{
    btnNavMenu.removeEventListener('click', openNavMenu);
    btnNavMenu.addEventListener('click', closeNavMenu);

    document.querySelector('#nav-links').classList.add('open-nav-menu');
}

btnNavMenu.addEventListener('click', openNavMenu);
