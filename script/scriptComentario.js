function mostrarEdicao(id) {
    document.getElementById('edicao-' + id).style.display = 'block';
}

function salvarComentario(id) {
    const novoTexto = document.getElementById('texto-' + id).value;

    fetch('../crudComentario/editar_comentario.php', {
        method: 'POST',
        headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
        body: 'id=' + id + '&comentario=' + encodeURIComponent(novoTexto)
    })
    .then(response => response.text())
    .then(data => {
    console.log('Resposta do PHP:', JSON.stringify(data));
    if (data.trim() === 'ok') {
        document.querySelector('#comentario-' + id + ' .texto-comentario').innerText = novoTexto;
        document.getElementById('edicao-' + id).style.display = 'none';
    } else {
        alert('Erro ao atualizar comentário.');
    }
});
}

