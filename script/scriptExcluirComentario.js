function excluirComentario(id) {
    if (!confirm('Tem certeza que deseja excluir este comentário?')) return;

    fetch('../crudComentario/excluir_comentario.php', {
        method: 'POST',
        headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
        body: 'id=' + id
    })
    .then(response => response.text())
    .then(data => {
        if (data.trim() === 'ok') {
            document.getElementById('comentario-' + id).remove();
        } else {
            alert('Erro ao excluir comentário.');
        }
    });
}
