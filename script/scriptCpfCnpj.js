function alternarCpfCnpj() {
    const tipo = document.getElementById('tipoDocumento').value;
    const campoCPF = document.getElementById('campoCPF');
    const campoCNPJ = document.getElementById('campoCNPJ');
    const cpfInput = document.getElementById('cpfInput');
    const cnpjInput = document.getElementById('cnpjInput');

    if (tipo === 'CPF') {
        campoCPF.style.display = 'block';
        campoCNPJ.style.display = 'none';
        cnpjInput.value = '';
        cpfInput.required = true;
        cnpjInput.required = false;
    } else if (tipo === 'CNPJ') {
        campoCPF.style.display = 'none';
        campoCNPJ.style.display = 'block';
        cpfInput.value = '';
        cpfInput.required = false;
        cnpjInput.required = true;
    } else {
        campoCPF.style.display = 'none';
        campoCNPJ.style.display = 'none';
        cpfInput.value = '';
        cnpjInput.value = '';
        cpfInput.required = false;
        cnpjInput.required = false;
    }
}

// Função para formatar CPF
function formatarCPF(input) {
    let valor = input.value.replace(/\D/g, '');
    if (valor.length <= 11) {
        valor = valor.replace(/(\d{3})(\d)/, '$1.$2');
        valor = valor.replace(/(\d{3})(\d)/, '$1.$2');
        valor = valor.replace(/(\d{3})(\d{1,2})$/, '$1-$2');
        input.value = valor;
    }
}

// Função para formatar CNPJ
function formatarCNPJ(input) {
    let valor = input.value.replace(/\D/g, '');
    if (valor.length <= 14) {
        valor = valor.replace(/^(\d{2})(\d)/, '$1.$2');
        valor = valor.replace(/^(\d{2})\.(\d{3})(\d)/, '$1.$2.$3');
        valor = valor.replace(/\.(\d{3})(\d)/, '.$1/$2');
        valor = valor.replace(/(\d{4})(\d)/, '$1-$2');
        input.value = valor;
    }
}

// Adicionar event listeners quando o documento carregar
document.addEventListener('DOMContentLoaded', function () {
    const cpfInput = document.getElementById('cpfInput');
    const cnpjInput = document.getElementById('cnpjInput');

    if (cpfInput) {
        cpfInput.addEventListener('input', function () {
            formatarCPF(this);
        });
    }

    if (cnpjInput) {
        cnpjInput.addEventListener('input', function () {
            formatarCNPJ(this);
        });
    }
});