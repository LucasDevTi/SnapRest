<div class="modal fade" id="cad-produtos-modal">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Produto</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form id="productsForm" method="POST" action="{{route('setProduct')}}">
                @csrf
                <div class="modal-body">
                    <div class="card-body">
                        <input type="hidden" name="id_produto" id="id_produto" value="">
                        <div class="row">
                            <div class="col-8">
                                <label for="product_name">Nome</label>
                                <input id="product_name" type="text" required name="product_name" class="form-control" required>
                            </div>

                            <div class="col-4">
                                <label for="product_price">Valor</label>
                                <input id="product_price" type="text" required name="product_price" class="form-control" required oninput="formatCurrency(this)">
                            </div>
                        </div>
                        <div class="row mt-2">
                            <div class="col-12">
                                <label for="ingredientes">Ingredientes</label>
                                <input id="ingredientes" type="text" name="ingredientes" class="form-control" placeholder="mussarela, ovo, salsicha,...">
                            </div>
                        </div>
                    </div>

                </div>
                <div class="modal-footer justify-content-between">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Fechar</button>
                    <button id="btn-salvar-produto" type="submit" onclick="saveProduct(event)" class="btn btn-primary">Salvar</button>
                </div>
            </form>
        </div>
        <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
</div>