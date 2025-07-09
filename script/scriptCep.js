document.addEventListener("DOMContentLoaded", function () {
    const cepInput = document.getElementById("cep");

    cepInput.addEventListener("blur", function () {
        const cep = cepInput.value.replace(/\D/g, ''); // remove tudo que não for número

        if (cep.length === 8) {
            fetch(`https://viacep.com.br/ws/${cep}/json/`)
                .then(response => response.json())
                .then(data => {
                    if (!data.erro) {
                        document.getElementById("rua").value = data.logradouro || '';
                        document.getElementById("bairro").value = data.bairro || '';
                        document.getElementById("cidade").value = data.localidade || '';
                        document.getElementById("estado").value = data.uf || '';
                    } else {
                        alert("CEP não encontrado.");
                        limparEndereco();
                    }
                })
                .catch(() => {
                    alert("Erro ao buscar o CEP.");
                    limparEndereco();
                });
        } else {
            limparEndereco();
        }
    });

    function limparEndereco() {
        document.getElementById("rua").value = '';
        document.getElementById("bairro").value = '';
        document.getElementById("cidade").value = '';
        document.getElementById("estado").value = '';
    }
});
