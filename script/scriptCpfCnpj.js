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
    } else if (tipo === 'CNPJ') {
        campoCPF.style.display = 'none';
        campoCNPJ.style.display = 'block';
        cpfInput.value = '';
    } else {
        campoCPF.style.display = 'none';
        campoCNPJ.style.display = 'none';
        cpfInput.value = '';
        cnpjInput.value = '';
    }
}