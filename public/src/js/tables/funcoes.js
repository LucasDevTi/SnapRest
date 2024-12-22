let products = [];
let productsToTransferred = [];
let idCurrentTableMenu = [];
let idCurrentTableTransferred = []
let totalPrice = 0;

let functionOnclick = '';
let background = '';

let selectingTables = false;
let arrayTablesSelects = [];

function resetModal() {
    $('.alert-success-small-modal').hide();
    $('.alert-error-small-modal').hide();
}

document.getElementById('search-input').addEventListener('input', function (event) {
    let searchTerm = event.target.value.toLowerCase(); // Obtém o termo de pesquisa em minúsculas
    let products = document.querySelectorAll('.content-products'); // Seleciona todos os produtos

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

/**
 * Função para abrir a comanda de uma mesa
 */
async function openOptionTable(table_id) {

    const token = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
    loadShow();

    try {

        const response = await fetch('get-table-status', {
            method: 'POST',
            headers: {
                'Content-type': 'application/json',
                'X-CSRF-TOKEN': token
            },
            body: JSON.stringify({ table_id: table_id })
        });

        if (!response.ok) {
            const errorData = await response.json();
            loadHide();
            throw new Error(`ERRO: ${errorData.message}`);
        }

        const data = await response.json();

        if (response.status === 200) {

            const modalOptionsTable = document.querySelector('#modal-table-options .modal-content');
            const options = modalOptionsTable.querySelector('.content-option');

            options.innerHTML = '';

            // Opção de abrir a comanda da mesa
            if (data.permissions.can_open_table) {

                functionOnclick = '';
                background = 'bg-gradient-secondary';

                if (data.status == 0 || data.status == 3) {
                    functionOnclick = `onclick="showProducts(${table_id})"`;
                    background = "bg-gradient-info";
                }

                options.innerHTML += `
                    <div id="box-open-table" class="info-box ${background} btn-table-options" ${functionOnclick}>
                        <i class="fas fa-user-plus"></i>
                        <span class="info-box-text">Abrir</span>
                    </div>
                `;
            }

            if (data.permissions.can_add_item) {
                functionOnclick = '';
                background = 'bg-gradient-secondary';

                if (data.status == 1) {
                    functionOnclick = `onclick="addItemsOntable(${table_id})"`;
                    background = "bg-gradient-info";
                }

                options.innerHTML += `
                    <div id="box-add-item-table" class="info-box ${background} btn-table-options" ${functionOnclick}>
                        <i class="fa fa-plus" aria-hidden="true"></i>
                        <span class="info-box-text">Adicionar</span>
                        <span class="info-box-text">item</span>
                    </div>
                `;
            }

            if (data.permissions.can_close_table) {
                functionOnclick = '';
                background = 'bg-gradient-secondary';

                if (data.status == 1) {
                    functionOnclick = `onclick="closeTable(${table_id})"`;
                    background = "bg-gradient-info";
                }

                options.innerHTML += `
                    <div id="box-close-table" class="info-box ${background} btn-table-options" ${functionOnclick}>
                        <i class="fa fa-hashtag" aria-hidden="true"></i>
                        <span class="info-box-text">Fechar</span>
                    </div>
                `;
            }

            if (data.permissions.can_transferred_table) {
                functionOnclick = '';
                background = 'bg-gradient-secondary';

                if (data.status == 1) {
                    functionOnclick = `onclick="openModalChangeTable(${table_id})"`;
                    background = "bg-gradient-info";
                }

                options.innerHTML += `
                    <div id="box-transferred-itens" data-value="1" class="info-box ${background} btn-table-options"  ${functionOnclick}>
                        <i class="fa fa-arrow-left" aria-hidden="true"></i>
                        <span class="info-box-text">Transferir</span>
                        <span class="info-box-text">itens</span>
                    </div>
                `;
            }

            $('#modal-table-options').modal('show');
            loadHide();
        } else {
            loadHide();
        }

    } catch (error) {
        // console.error('Erro ao atualizar o status da mesa:', error);
        loadHide();

    }
}

/**
 * Função para listar os produtos disponiveis para venda
 */
async function showProducts(table_id) {
    loadShow();
    const token = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

    products = [];
    productsToTransferred = [];
    idCurrentTableMenu = [];
    idCurrentTableTransferred = []
    totalPrice = 0;

    const spanTotalPrice = document.getElementById('span-total-price');
    spanTotalPrice.textContent = `R$ ${totalPrice.toFixed(2)}`;

    try {
        const response = await fetch('/get-products', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': token, // Inclua o token CSRF para segurança
            },
            body: JSON.stringify({}) // Exemplo de novo status
        });

        if (!response.ok) {
            const errorData = await response.json();
            throw new Error(`ERRO: ${errorData.message}`);
        }

        const data = await response.json();

        if (data.success) {

            const productContainer = document.getElementById('products-list');
            productContainer.innerHTML = '';

            data.data.forEach(product => {
                const productElement = `
                <div class="content-products col-sm-12">
                    <div class="div-products col-sm-8" id="products-list">
                        <div class="div-item-prod" style="flex:1;">
                            <div class="desc-product">
                                <h2>${product.name}</h2>
                               
                                <input type="text" class="form-control" style="width:100%;"  placeholder="Observações" />
                            </div>
                            <div class="price-product" style="margin-top:0px;">
                                R$ ${product.price}
                            </div>
                        </div>
                        <div class="qtde-item text-success">
                            <input type="number" id="qtde_${product.id}" value="0" class="text-success" disabled />
                        </div>
                    </div>
                    <div class="content-qtde">
                        <i class="fa fa-plus-circle plus-qtde" aria-hidden="true" onclick="addProduct(${product.id}, ${product.price}, ${table_id})"></i>
                        <i class="fa fa-minus-circle minus-qtde" aria-hidden="true" onclick="rmvProduct(${product.id}, ${product.price}, ${table_id})"></i>    
                    </div>
                </div>
            `;
                productContainer.insertAdjacentHTML('beforeend', productElement);
            });

            $('#modal-table-options').modal('hide');
            $('#modal-products-options').modal('show');
        }
        loadHide();

        // Aqui você pode fazer algo após a atualização, como recarregar a lista de mesas

    } catch (error) {
        console.error('Erro ao buscar produtos:', error);
        loadHide();
    }
}

/**
 * Função para adicionar um produto ao json de produtos que serão pedidos
 */
function addProduct(product_id, price, table_id) {
    // Verifica se o produto já existe no array
    let product = products.find(p => p.id === product_id);

    if (product) {
        // Se o produto já existe, aumenta a quantidade e recalcula o valor total
        product.quantity += 1;
    } else {
        // Se o produto não existir, cria um novo objeto e adiciona ao array
        product = { id: product_id, price: price, quantity: 1 };
        products.push(product);
        idCurrentTableMenu = table_id;
    }

    // Recalcular o valor total
    totalPrice += price;

    // Atualiza o valor no input
    const inputProduct = document.getElementById(`qtde_${product_id}`);
    inputProduct.value = product.quantity;

    // Atualiza o valor total na tela
    const spanTotalPrice = document.getElementById('span-total-price');
    spanTotalPrice.textContent = `R$ ${totalPrice.toFixed(2)}`;

    // console.log(produtos);
}

/**
 * Função para removar um produto do json de produtos que serão pedidos
 */
function rmvProduct(product_id, price, table_id) {
    // Encontra o produto no array
    let product = products.find(p => p.id === product_id);

    if (product && product.quantity > 0) {
        // Se o produto existir e a quantidade for maior que 0, diminui a quantidade
        product.quantity -= 1;

        // Recalcular o valor total
        totalPrice -= price;

        // Atualiza o valor no input
        const inputProduct = document.getElementById(`qtde_${product_id}`);
        inputProduct.value = product.quantity;

        // Atualiza o valor total na tela
        const spanTotalPrice = document.getElementById('span-total-price');
        spanTotalPrice.textContent = `R$ ${totalPrice.toFixed(2)}`;

        // Se a quantidade chegar a 0, remove o produto do array
        if (product.quantidade === 0) {
            products = products.filter(p => p.id !== product_id);
        }

        idCurrentTableMenu = table_id;
    }
}

/**
 * Função para realizar um pedido
 */
async function setOrder(event) {
    loadShow();
    event.preventDefault();
    const token = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
    const productsJson = JSON.stringify(products);

    try {
        const response = await fetch('/set-order', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': token, // Inclua o token CSRF para segurança
            },
            body: JSON.stringify({ productsData: productsJson, table_id: idCurrentTableMenu })
        });

        if (!response.ok) {
            const errorData = await response.json();
            throw new Error(`ERRO: ${errorData.message}`);
            loadHide();
        }

        const data = await response.json();

        if (data.success) {
            loadHide();
            location.reload();
        }

    } catch (error) {
        loadHide();
        console.error('Erro ao atualizar o status da mesa:', error);
    }
    document.getElementById('productsData').value = productsJson;
};

/**
 * Função para adicionar itens na mesa
 */
async function addItemsOntable(table_id) {

    loadShow();
    const token = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

    products = [];
    productsToTransferred = [];
    idCurrentTableMenu = [];
    idCurrentTableTransferred = []
    totalPrice = 0;

    const spanTotalPrice = document.getElementById('span-total-price');
    spanTotalPrice.textContent = `R$ ${totalPrice.toFixed(2)}`;

    try {
        const response = await fetch('/get-products', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': token,
            },
            body: JSON.stringify({})
        });

        if (!response.ok) {
            const errorData = await response.json();
            loadHide();
            throw new Error(`ERRO: ${errorData.message}`);
        }

        const data = await response.json();

        if (data.success) {

            const productContainer = document.getElementById('products-list');
            productContainer.innerHTML = '';

            data.data.forEach(product => {
                const productElement = `
                <div class="content-products col-sm-12">
                    <div class="div-products col-sm-8" id="products-list">
                        <div class="div-item-prod" style="flex:1;">
                            <div class="desc-product">
                                <h2>${product.name}</h2>
                               
                                <input type="text" class="form-control" style="width:100%;"  placeholder="Observações" />
                            </div>
                            <div class="price-product" style="margin-top:0px;">
                                R$ ${product.price}
                            </div>
                        </div>
                        <div class="qtde-item text-success">
                            <input type="number" id="qtde_${product.id}" value="0" class="text-success" disabled />
                        </div>
                    </div>
                    <div class="content-qtde">
                        <i class="fa fa-plus-circle plus-qtde" aria-hidden="true" onclick="addProduct(${product.id}, ${product.price}, ${table_id})"></i>
                        <i class="fa fa-minus-circle minus-qtde" aria-hidden="true" onclick="rmvProduct(${product.id}, ${product.price}, ${table_id})"></i>    
                    </div>
                </div>
            `;
                productContainer.insertAdjacentHTML('beforeend', productElement);
            });

            $('#modal-table-options').modal('hide');
            $('#modal-products-options').modal('show');

            loadHide();
        }
        loadHide();
        // Aqui você pode fazer algo após a atualização, como recarregar a lista de mesas

    } catch (error) {
        loadHide();
        console.error('Erro ao buscar produtos:', error);
    }
}

/**
 * Função para fechar uma mesa
 */
async function closeTable(table_id) {
    loadShow();
    const token = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

    try {
        const response = await fetch('/close-tables', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': token,
            },
            body: JSON.stringify({ table_id: table_id })
        });

        if (!response.ok) {
            const errorData = await response.json();
            loadHide();
            throw new Error(`ERRO: ${errorData.message}`);
        }

        const data = await response.json();

        if (data.success) {
            loadHide();
            location.reload();
        }

    } catch (error) {
        loadHide();
        // console.log('Erro', error);
    }
}

function enableSelectTableMode() {
    selectingTables = true;
    arrayTablesSelects = []; // Limpa as mesas selecionadas

    document.getElementById('btn-selected-tables-linked').innerText = "Cancelar Seleção";
    document.getElementById('btn-linked-tables').style.display = 'block';
    document.getElementById('btn-reservation').style.display = 'none';

    // Troca o texto do botão para "Cancelar Seleção" e alterna ao clicar novamente
    document.getElementById('btn-selected-tables-linked').onclick = disableSelectTable;

    const tables = document.querySelectorAll('.table');

    tables.forEach(table => {

        const status = table.getAttribute('data-status');
        // console.log(table)
        if (status == "0" || status == "1") {

            // Remove o evento "onclick" existente
            table.removeAttribute('onclick');

            // Adiciona um novo evento com a função selectTableForLinked
            table.setAttribute('onclick', 'selectTableForLinked(this)');
        }
    });
}

function disableSelectTable() {

    location.reload();
    // selectingTables = false;
    // arrayTablesSelects = []; // Limpa a seleção

    // document.getElementById('btn-selected-tables-linked').innerText = "Juntar Mesas";
    // document.getElementById('btn-selected-tables-linked').onclick = enableSelectTableMode;

    // document.getElementById('btn-linked-tables').style.display = 'none';
    // document.getElementById('btn-reservation').style.display = 'block';

    // // Remove a classe de seleção de todas as mesas
    // const mesas = document.querySelectorAll('.table-selected');
    // mesas.forEach(mesa => {
    //     mesa.classList.remove('table-selected');
    // });
}

function selectTableForLinked(element) {
    const table_id = element.dataset.idtable;

    if (arrayTablesSelects.includes(table_id)) {
        // Se a mesa já está selecionada, removê-la da seleção
        arrayTablesSelects = arrayTablesSelects.filter(id => id !== table_id);
        element.classList.remove("table-selected");
    } else {
        // Adiciona a mesa à lista de selecionadas
        arrayTablesSelects.push(table_id);
        element.classList.add("table-selected");
    }

    // console.log("Mesas Selecionadas:", arrayTablesSelects);
}


async function linkedTables() {

    if (arrayTablesSelects.length === 0) {
        $('.alert-success-small-modal').hide();
        $('.alert-error-small-modal').show();
        $('.alert-error-small-modal').html("Precisa de uma mesa principal");
        return
    }

    const PrincipalTable = $('#table_principal').val();
    loadShow();

    if (arrayTablesSelects.includes(PrincipalTable)) {
        // console.log(arrayTablesSelects)
        // return
        resetModal();

        const token = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

        try {

            const response = await fetch('/link-tables', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': token, // Inclua o token CSRF para segurança
                },
                body: JSON.stringify({ arrayTablesSelects: arrayTablesSelects, PrincipalTable: PrincipalTable })
            });

            if (!response.ok) {
                const errorData = await response.json();
                loadHide();
                throw new Error(`ERRO: ${errorData.message}`);
            }

            const data = await response.json();

            if (data.success) {

                location.reload();

                // $('#opcoes_mesa').modal('hide');

                // enableOptionTable(mesa, table_id, data.status)
            }
        } catch (error) {
            loadHide();
            // console.error('Erro ao juntar as mesa:', error);
        }

    } else {
        $('.alert-success-small-modal').hide();
        $('.alert-error-small-modal').show();
        $('.alert-error-small-modal').html("A mesa principal precisa ser uma  das mesas selecionadas.");
        loadHide();
        return
    }
}

async function openModalChangeTable(table_id) {

    loadShow();

    const radioButtons = document.querySelectorAll('input[name="transferred-option"]');
    radioButtons.forEach(radio => {
        radio.checked = false;
    });

    products = [];
    productsToTransferred = [];
    idCurrentTableMenu = [];
    idCurrentTableTransferred = []
    totalPrice = 0;

    const selectMesas = document.getElementById('tables-availables-transferred');
    selectMesas.innerHTML = '<option selected value="0">Selecione</option>';
    const token = document.querySelector('meta[name="csrf-token"]').getAttribute('content');


    try {
        const response = await fetch('/get-itens-by-table', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': token,
            },
            body: JSON.stringify({ table_id: table_id })
        });

        if (!response.ok) {
            const errorData = await response.json();
            loadHide();
            throw new Error(`ERRO: ${errorData.message}`);
        }

        const data = await response.json();

        if (data.success) {

            const productOrderContainer = document.querySelector('.content-products-order-list');
            productOrderContainer.innerHTML = '';
            productOrderContainer.style.display = 'none';

            data.orderItems.forEach(product => {
                const productElement = `
                <div class="products-order-list">
                    <div class="item-da-mesa">                            
                        <span><strong>${product.product_name} | quantidade: ${product.quantity}</strong></span>
                    </div>
                    <div class="content-qtde-transferencia">  
                        <i class="fa fa-plus-circle plus-qtde" aria-hidden="true" onclick="addProductTransferencia(${product.product_id}, ${product.quantity}, ${table_id})"></i>
                        <input type="number" class="form-control qtde-max-tranferencia" id="qtde-max-${product.product_id}" value="0" disabled>
                        <i class="fa fa-minus-circle minus-qtde" aria-hidden="true" onclick="rmvProductTransferencia(${product.product_id}, ${product.quantity}, ${table_id})"></i>    
                    </div>
                </div>
            `;
                productOrderContainer.insertAdjacentHTML('beforeend', productElement);
            });

            const selectedRadio = document.querySelector('input[name="transferred-option"]:checked');

            // Verifica se o rádio selecionado tem o valor "2"
            if (selectedRadio && selectedRadio.value == 2) {
                productOrderContainer.style.display = 'flex';
            }
            data.tables.forEach(table => {
                const option = document.createElement('option');
                option.value = table.id;
                option.textContent = `Mesa: ${table.id} - ${table.description_status}`;
                selectMesas.appendChild(option);
            });

            $('#modal-table-options').modal('hide');
            $('#modal-transferred-itens').modal('show');
            loadHide();
        }
        loadHide();

    } catch (error) {
        loadHide();
        console.error('Erro ao buscar produtos:', error);
    }
}

function handleChange(element) {

    const productOrderContainer = document.querySelector('.content-products-order-list');

    if (element.value == 1) {
        productOrderContainer.style.display = 'none';
    } else {
        productOrderContainer.style.display = 'flex';
    }
}


function addProductTransferencia(product_id, quantity, table_id) {
    // Verifica se o produto já existe no array
    let product = productsToTransferred.find(p => p.id === product_id);
    idCurrentTableTransferred = table_id;

    if (product) {
        product.quantity += 1;

        if (product.quantity > quantity) {
            product.quantity = quantity;
        }

        document.getElementById(`qtde-max-${product_id}`).value = product.quantity

    } else {
        // Se o produto não existir, cria um novo objeto e adiciona ao array
        product = { id: product_id, quantity: 1 };
        productsToTransferred.push(product);
        document.getElementById(`qtde-max-${product_id}`).value = 1

    }

    console.log(productsToTransferred);

}

function rmvProductTransferencia(product_id, quantity, table_id) {
    // Encontra o produto no array
    let product = productsToTransferred.find(p => p.id === product_id);
    idCurrentTableTransferred = table_id;

    if (product && product.quantity > 0) {
        // Se o product existir e a quantidade for maior que 0, diminui a quantidade
        product.quantity -= 1;
        // Se a quantidade chegar a 0, remove o product do array
        if (product.quantity === 0) {
            productsToTransferred = productsToTransferred.filter(p => p.id !== product_id);
            document.getElementById(`qtde-max-${product_id}`).value = 0
        }
        document.getElementById(`qtde-max-${product_id}`).value = product.quantity

    }

    console.log(productsToTransferred);

}

async function transferredItens(event) {

    event.preventDefault();

    const token = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
    const productsJson = JSON.stringify(productsToTransferred);
    const radios = document.getElementsByName('transferred-option');

    let opcao = 0;
    console.log(productsJson)
    console.log(idCurrentTableTransferred);
    const tableToTransferred = document.getElementById('tables-availables-transferred');
    for (let radio of radios) {
        if (radio.checked) {
            opcao = radio.value;
            break;
        }
    }

    console.log(opcao);
    console.log(tableToTransferred.value);

    // return
    if (parseInt(opcao) != 0 && parseInt(tableToTransferred.value) != 0) {
        try {
            const response = await fetch('/set-transferred', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': token,
                },
                body: JSON.stringify({ productsData: productsJson, table_id: idCurrentTableTransferred, opcao: opcao, tableToTransferred: tableToTransferred.value })
            });

            if (!response.ok) {
                const errorData = await response.json();
                throw new Error(`ERRO: ${errorData.message}`);
            }

            const data = await response.json();

            if (data.success) {

                location.reload();
            }

        } catch (error) {
            console.error('Erro ao atualizar o status da mesa:', error);
        }
        document.getElementById('productsData').value = productsJson;
    }

};