// Menu burger (mobile)
const burgerToggle = document.getElementById('burger-toggle');
const navMenu = document.getElementById('nav-menu');

if (burgerToggle && navMenu) {
    burgerToggle.addEventListener('click', function () {
        const isOpen = navMenu.classList.toggle('open');
        burgerToggle.setAttribute('aria-expanded', isOpen ? 'true' : 'false');
        burgerToggle.textContent = isOpen ? '✕' : '☰';
    });
}

// Bouton like (AJAX, sans recharger la page)
document.querySelectorAll('.like-form').forEach(function (form) {
    form.addEventListener('submit', async function (event) {
        event.preventDefault();

        const button = form.querySelector('.btn-like');
        const formData = new FormData(form);

        const response = await fetch(form.action, {
            method: 'POST',
            body: formData,
            headers: { 'X-Requested-With': 'XMLHttpRequest' }
        });
        const data = await response.json();

        button.textContent = '♥ ' + data.count;
        button.classList.toggle('liked', data.liked);
    });
});
