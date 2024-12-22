<div class="modal fade" id="modal-products-options">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Produto</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form method="POST" action="{{route('set-order')}}">
                @csrf
                <div class="modal-body">
                    {{-- <input type="hidden" id="productsData" name="productsData">
                    <input type="hidden" id="mesaAtual" name="productsData"> --}}

                    <div class="search-container">
                        <input type="text" id="search-input" class="form-control" placeholder="Pesquisar produtos...">
                    </div>
                    <div class="card-body" style="height:400px; overflow-y:scroll;">
                        <div id="products-list">
                            <!-- Produtos serão inseridos aqui pelo JavaScript -->
                        </div>
                    </div>

                </div>
                <div class="modal-footer justify-content-between">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Fechar</button>
                    <div id="box-total-price">total: <span id="span-total-price">0,00</span></div>
                    <button type="submit" onclick="setOrder(event)" class="btn btn-primary">Finalizar</button>
                </div>  
            </form>
        </div>
        <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
</div>