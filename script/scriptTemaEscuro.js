// Carrega o tema salvo
document.addEventListener('DOMContentLoaded', function () {
    const isDark = localStorage.getItem('modo') === 'dark';
    if (isDark) {
        document.body.classList.add('dark-mode');
    }

    const btn = document.getElementById('toggle-dark');
    if (btn) {
        btn.addEventListener('click', function () {
            document.body.classList.toggle('dark-mode');
            const modoAtual = document.body.classList.contains('dark-mode') ? 'dark' : 'light';
            localStorage.setItem('modo', modoAtual);
        });
    }

    const btnToggle = document.getElementById("toggle-dark");

    // Alterna o ícone ao clicar
    btnToggle.addEventListener("click", () => {
        if (btnToggle.textContent.includes("🌙")) {
            btnToggle.textContent = "☀️ Modo Claro";
        } else {
            btnToggle.textContent = "🌙 Modo Escuro";
        }
    });

});

