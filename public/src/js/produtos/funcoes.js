function formatCurrency(input) {
    let value = input.value.replace(/\D/g, ''); // Remove todos os caracteres não numéricos
    value = (value / 100).toFixed(2);           // Divide por 100 para obter os centavos e mantém 2 casas decimais
    value = value.replace('.', ',');            // Substitui o ponto decimal por vírgula
    value = value.replace(/\B(?=(\d{3})+(?!\d))/g, '.'); // Adiciona o ponto como separador de milhar
    input.value = `R$ ${value}`;                // Adiciona o símbolo de Real (R$) ao início
}

document.getElementById('search-input').addEventListener('input', function (event) {
    let searchTerm = event.target.value.toLowerCase(); // Obtém o termo de pesquisa em minúsculas
    let products = document.querySelectorAll('.div-products'); // Seleciona todos os produtos

    products.forEach(function (product) {
        let productName = product.querySelector('.desc-product h2').textContent.toLowerCase(); // Nome do produto
        // Verifica se o nome do produto inclui o termo de pesquisa
        if (productName.includes(searchTerm)) {
            product.style.display = ''; // Exibe o produto
        } else {
            product.style.display = 'none'; // Oculta o produto
        }
    });
});

function showModalProdEdit(name, ingredients, price, id) {
    const inputProdName = document.getElementById('product_name');
    const inputProdIngredients = document.getElementById('ingredientes');
    const inputProdPrice = document.getElementById('product_price');
    const inputIdProduto = document.getElementById('id_produto');

    inputProdName.value = name;
    inputProdIngredients.value = ingredients;
    inputProdPrice.value = price;

    document.getElementById('productsForm').action = '/editProduct';
    inputIdProduto.value = id;

    $('#cad-produtos-modal').modal('show');
}

async function excluirProduto(event, id) {
    event.stopPropagation();
    const token = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

    if (confirm('Deseja excluir esse produto?')) {
        try {
            const response = await fetch('/deletar-produto', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': token, // Inclua o token CSRF para segurança
                },
                body: JSON.stringify({ id: id }) // Exemplo de novo status
            });

            if (!response.ok) {
                const errorData = await response.json();
                throw new Error(`ERRO: ${errorData.message}`);
            }
            // console.log(response.json);

            const data = await response.json();

            if (data.success) {

                location.reload();
            }

            // Aqui você pode fazer algo após a atualização, como recarregar a lista de mesas

        } catch (error) {
            console.error('Erro ao atualizar o status da mesa:', error);
        }
    }
}

function saveProduct(event) {

    event.preventDefault();

    const name = document.getElementById('product_name').value;
    const price = document.getElementById('product_price').value
    const button = document.getElementById('btn-salvar-produto');

    if (name != '' && price != '') {
        button.disabled = true;
        document.getElementById('productsForm').submit();
    }
}