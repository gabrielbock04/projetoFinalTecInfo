console.log('scriptMenu.js carregado');
function toggleMenu() {
    const menu = document.getElementById('miniTela');
    const btn = document.querySelector('.btn-interno');

    // Alterna a classe para abrir/fechar o menu
    menu.classList.toggle('show');

    // Alterna a animação do botão hamburguer
    function toggleMenu() {
        const nav = document.querySelector('.header-nav');
        nav.classList.toggle('ativo');
    }
}

// Função para abrir/fechar o menu do usuário
function toggleUserMenu(event) {
    console.log('toggleUserMenu chamado');
    event && event.preventDefault && event.preventDefault();
    const dropdown = document.getElementById('user-dropdown');
    const btn = document.querySelector('.hamburguer-user');
    console.log('dropdown encontrado:', dropdown);
    console.log('btn encontrado:', btn);
    const isOpen = dropdown.classList.contains('show');
    console.log('menu está aberto:', isOpen);
    if (!isOpen) {
        dropdown.classList.add('show');
        console.log('menu aberto');
        setTimeout(() => {
            document.addEventListener('mousedown', closeUserMenuOnClickOutside);
            document.addEventListener('keydown', closeUserMenuOnEsc);
        }, 0);
    } else {
        dropdown.classList.remove('show');
        console.log('menu fechado');
        document.removeEventListener('mousedown', closeUserMenuOnClickOutside);
        document.removeEventListener('keydown', closeUserMenuOnEsc);
    }
}
function closeUserMenuOnClickOutside(e) {
    const dropdown = document.getElementById('user-dropdown');
    const btn = document.querySelector('.hamburguer-user');
    if (!dropdown.contains(e.target) && !btn.contains(e.target)) {
        dropdown.classList.remove('show');
        document.removeEventListener('mousedown', closeUserMenuOnClickOutside);
        document.removeEventListener('keydown', closeUserMenuOnEsc);
    }
}
function closeUserMenuOnEsc(e) {
    if (e.key === 'Escape') {
        const dropdown = document.getElementById('user-dropdown');
        dropdown.classList.remove('show');
        document.removeEventListener('mousedown', closeUserMenuOnClickOutside);
        document.removeEventListener('keydown', closeUserMenuOnEsc);
    }
}
// Acessibilidade: abrir/fechar com Enter/Espaço
window.addEventListener('DOMContentLoaded', function() {
    const btn = document.querySelector('.hamburguer-user');
    console.log('hamburguer-user encontrado:', btn);
    if (btn) {
        btn.addEventListener('keydown', function(e) {
            if (e.key === 'Enter' || e.key === ' ') {
                e.preventDefault();
                toggleUserMenu(e);
            }
        });
    }
});
// Garante que toggleUserMenu está disponível globalmente para o onclick HTML
window.toggleUserMenu = toggleUserMenu;

// Carrossel vertical de anúncios (2 linhas de 3 anúncios, rolagem automática)
let anunciosCarouselIndex = 0;
let anunciosCarouselTimer = null;
function autoScrollAnuncios() {
    const carousel = document.querySelector('.anuncios-carousel');
    const rows = document.querySelectorAll('.anuncios-carousel-row');
    const visibleCount = 2; // 2 linhas de 3 anúncios
    if (!carousel || rows.length <= visibleCount) return;
    anunciosCarouselIndex++;
    if (anunciosCarouselIndex > rows.length - visibleCount) {
        anunciosCarouselIndex = 0;
    }
    // Força o cálculo da altura da linha após renderização
    const rowHeight = rows[0].offsetHeight + 18; // 18px gap
    carousel.style.transform = `translateY(-${anunciosCarouselIndex * rowHeight}px)`;
}
window.addEventListener('DOMContentLoaded', function() {
    setTimeout(() => {
        if (anunciosCarouselTimer) clearInterval(anunciosCarouselTimer);
        anunciosCarouselIndex = 0;
        const carousel = document.querySelector('.anuncios-carousel');
        if (carousel) {
            carousel.style.transform = 'translateY(0)';
            anunciosCarouselTimer = setInterval(autoScrollAnuncios, 3000);
        }
    }, 500);
});

// Menu mobile responsivo
function toggleMobileMenu() {
    const menu = document.getElementById('mobile-menu');
    if (menu.classList.contains('show')) {
        menu.classList.remove('show');
        document.body.style.overflow = '';
    } else {
        menu.classList.add('show');
        document.body.style.overflow = 'hidden';
        setTimeout(() => {
            menu.querySelector('a,button').focus();
        }, 100);
    }
}
document.addEventListener('DOMContentLoaded', function() {
    const btn = document.getElementById('mobile-menu-btn');
    if (btn) {
        btn.addEventListener('click', toggleMobileMenu);
    }
    // Fechar ao clicar em link ou botão do menu
    document.getElementById('mobile-menu').addEventListener('click', function(e) {
        if (e.target.tagName === 'A' || e.target.classList.contains('close-mobile-menu') || e.target.tagName === 'BUTTON') {
            toggleMobileMenu();
        }
    });
    // Fechar com ESC
    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape') {
            const menu = document.getElementById('mobile-menu');
            if (menu.classList.contains('show')) toggleMobileMenu();
        }
    });
});