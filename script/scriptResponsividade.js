// Controlar o menu hambúrguer
const hamburguer = document.getElementById('hamburguer');
const menuNav = document.getElementById('menu-nav');

hamburguer.addEventListener('click', () => {
    menuNav.classList.toggle('active');
});