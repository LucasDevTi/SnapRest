function mascaraTelefone(input) {
    let valor = input.value;

    // Remove tudo o que não for dígito
    valor = valor.replace(/\D/g, "");

    // Adiciona parênteses ao redor do código de área
    if (valor.length > 2) {
        valor = '(' + valor.substring(0, 2) + ') ' + valor.substring(2);
    }

    // Adiciona um espaço após os primeiros 6 números
    if (valor.length > 10) {
        valor = valor.substring(0, 10) + '-' + valor.substring(10, 14);
    }

    // Atualiza o valor do campo de input
    input.value = valor;
}