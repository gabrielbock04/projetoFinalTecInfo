function alternarCampos() {
    const tipo = document.getElementById("tipoDocumento").value;
    document.getElementById("campoCPF").style.display = tipo === "cpf" ? "block" : "none";
    document.getElementById("campoCNPJ").style.display = tipo === "cnpj" ? "block" : "none";
}

function validarFormulario() {
    const tipo = document.getElementById("tipoDocumento").value;
    const cpf = document.getElementById("cpf").value;
    const cnpj = document.getElementById("cnpj").value;
    const campoFinal = document.getElementById("cpfCnpjFinal");

    if (tipo === "cpf" && cpf.length === 11) {
        campoFinal.value = cpf;
    } else if (tipo === "cnpj" && cnpj.length === 14) {
        campoFinal.value = cnpj;
    } else {
        alert("Preencha corretamente o CPF ou CNPJ.");
        return false;
    }

    return true;
}
