<div class="modal fade" id="modal-transferred-itens">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Transferir itens</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form id="form-transferred" method="POST" action="{{route('set-transferred')}}">
                @csrf

                <div class="modal-body">
                    <input type="hidden" id="current-table-transferred" name="current-table-transferred">

                    <div class="card-body" style="height:400px; overflow-y:scroll;">
                        <div class="custom-control custom-radio">
                            <input class="custom-control-input" type="radio" id="transferred-all-itens" name="transferred-option" value="1" onchange="handleChange(this)">
                            <label for="transferred-all-itens" class="custom-control-label">Transferir todos os itens</label>
                        </div>
                        <div class="custom-control custom-radio">
                            <input class="custom-control-input" type="radio" id="transfere-alguns" name="transferred-option" value="2" onchange="handleChange(this)">
                            <label for="transfere-alguns" class="custom-control-label">Escolher itens para trasferência</label>
                        </div>

                        <div class="content-products-order-list" style="display: none; flex-direction: column;">

                        </div>

                        <div class="form-group" style="margin-top:10px">
                            <label>Transferir para a mesa:</label>
                            <select class="form-control" id="tables-availables-transferred" name="tables_availables_transferred">
                                <option selected value="0">Selecione</option>
                            </select>
                        </div>
                    </div>

                </div>
                <div class="modal-footer justify-content-between">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Fechar</button>
                    <button type="submit" onclick="transferredItens(event)" class="btn btn-primary">Transferir</button>
                </div>  
            </form>
        </div>
    </div>
</div>