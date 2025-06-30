document.addEventListener('DOMContentLoaded', function () {
    const carrossel = document.querySelector('.carrossel-destaques');
    const cards = carrossel.querySelectorAll('.destaque-card');
    let idx = 0;
    const cardWidth = cards[0].offsetWidth + 32;

    setInterval(() => {
        idx = (idx + 1) % cards.length;
        carrossel.scrollTo({
            left: idx * cardWidth,
            behavior: 'smooth'
        });
    }, 5000);
});

document.addEventListener('DOMContentLoaded', function () {
    const slides = document.querySelectorAll('.depoimento-slide');
    let indiceAtual = 0;
    let animando = false;

    function mostrarSlideAnimado(novoIndice) {
        if (animando || novoIndice === indiceAtual) return;
        animando = true;
        const slideAtual = slides[indiceAtual];
        const proximo = slides[novoIndice];

        // Prepara o próximo slide para entrar pela direita
        proximo.classList.remove('ativo', 'saindo');
        proximo.style.transition = 'none';
        proximo.style.transform = 'translateX(100%)';
        proximo.offsetHeight; // força reflow
        proximo.style.transition = '';
        proximo.classList.add('ativo');
        proximo.style.transform = 'translateX(0)'; // <-- ESSENCIAL

        // Anima o slide atual para a esquerda
        slideAtual.classList.remove('ativo');
        slideAtual.classList.add('saindo');
        slideAtual.style.transform = 'translateX(-100%)';

        // Após a animação, reseta o slide anterior para a direita
        setTimeout(() => {
            slideAtual.classList.remove('saindo');
            slideAtual.style.transition = 'none';
            slideAtual.style.transform = 'translateX(100%)';
            slideAtual.offsetHeight; // força reflow
            slideAtual.style.transition = '';
            indiceAtual = novoIndice;
            animando = false;
        }, 700);
    }

    function proximoSlide() {
        let novoIndice = (indiceAtual + 1) % slides.length;
        mostrarSlideAnimado(novoIndice);
    }

    // Inicializa
    slides.forEach((slide, i) => {
        slide.classList.remove('ativo', 'saindo');
        slide.style.transform = 'translateX(100%)';
    });
    slides[0].classList.add('ativo');
    slides[0].style.transform = 'translateX(0)';

    setInterval(proximoSlide, 6000); // Troca a cada 6 segundos
});

document.addEventListener('DOMContentLoaded', function () {
    const galeria = document.querySelector('.galeria-carrossel');
    const pontos = document.querySelectorAll('.timeline-point');
    const cards = document.querySelectorAll('.galeria-card');

    function ativarPonto(indice) {
        pontos.forEach(p => p.classList.remove('ativo'));
        cards.forEach(c => c.classList.remove('ativo'));
        if (pontos[indice]) pontos[indice].classList.add('ativo');
        if (cards[indice]) cards[indice].classList.add('ativo');
    }

    galeria.addEventListener('scroll', function () {
        const scrollLeft = galeria.scrollLeft;
        const cardWidth = cards[0].offsetWidth + 50; // largura + gap
        const idx = Math.round(scrollLeft / cardWidth);
        ativarPonto(idx);
    });

    // Clique na timeline leva para o card correspondente
    pontos.forEach((p, idx) => {
        p.addEventListener('click', () => {
            const cardWidth = cards[0].offsetWidth + 50; // gap real
            let scrollTo = idx * cardWidth;
            if (idx === cards.length - 1) {
                // Scroll até o final real da galeria, considerando padding e scroll máximo
                scrollTo = galeria.scrollWidth - galeria.clientWidth;
            }
            galeria.scrollTo({
                left: scrollTo,
                behavior: 'smooth'
            });
            ativarPonto(idx);
        });
    });
    // Inicializa o primeiro card como ativo
    ativarPonto(0);
});

const slides = document.getElementById('carrossel-jornal-slides');
const totalSlides = slides.children.length;
let idx = 0;

setInterval(() => {
    idx = (idx + 1) % totalSlides;
    slides.style.transform = `translateY(-${idx * 505}px)`;
}, 5000);