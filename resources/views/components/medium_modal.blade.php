<div class="modal fade" id="continua-reserva-cliente">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Cliente</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form id="clienteForm" method="POST" action="{{route('setReserva')}}">
                @csrf
                <div class="modal-body">


                    <div class="card-body">
                        <div class="row">
                            <div class="col-8">
                                <label for="name">Nome</label>
                                <input id="client_name" type="text" required name="name" class="form-control" required>
                            </div>
                            <div class="col-4">
                                <label for="cpf_cnpj">CPF/CNPJ</label>
                                <input id="client_cpf_cnpj" type="text" name="cpf_cnpj" class="form-control">
                            </div>
                        </div>

                        <div class="row mt-2">
                            <div class="col-4">
                                <label for="rua">Rua</label>
                                <input id="client_rua" type="text" name="rua" class="form-control">
                            </div>
                            <div class="col-4">
                                <label for="bairro">Bairro</label>
                                <input id="client_bairro" type="text" name="bairro" class="form-control">
                            </div>
                            <div class="col-2">
                                <label for="numero">N°</label>
                                <input id="client_numero" type="number" name="numero" class="form-control">
                            </div>
                            <div class="col-2">
                                <label for="complemento">Complemento</label>
                                <input id="client_complemento" type="text" name="complemento" class="form-control">
                            </div>
                        </div>

                        <div class="row mt-2">
                            <!-- <div class="col-6">
                                <label for="client_phone_1">Telefone 1</label>
                                <input id="client_phone_1" type="text" name="client_phone_1" class="form-control" placeholder="(xx) xxxxx-xxxx" maxlength="15" oninput="mascaraTelefone(this)">
                            </div> -->
                            <div class="col-6">
                                <label for="client_cellphone">Celular</label>
                                <input type="text" class="form-control" required id="client_cellphone" name="client_cellphone" placeholder="(xx) xxxxx-xxxx" maxlength="15" oninput="mascaraTelefone(this)" required>
                            </div>

                            <div class="col-6">
                                <label for="client_email">Email</label>
                                <input id="client_email" type="email" name="client_email" class="form-control">
                            </div>
                        </div>

                        <!-- <div class="row mt-2">
                            <div class="col-12">
                                <label for="client_email">Email</label>
                                <input id="client_email" type="email" name="client_email" class="form-control">
                            </div>
                        </div> -->

                        <div class="row mt-2">
                            <div class="col-12">
                                <label for="client_obs">Observação</label>
                                <input id="client_obs" type="text" name="client_obs" class="form-control">
                            </div>
                        </div>

                        <div class="row mt-2">
                            <div class="col-12">
                                <div class="form-group">
                                    <label>Mesa</label>
                                    <select class="form-control" id="mesas-disponiveis" name="mesas_disponiveis">
                                        <option selected value="0">Selecione</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>

                </div>
                <div class="modal-footer justify-content-between">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Fechar</button>
                    <button type="submit" class="btn btn-primary">Reservar</button>
                </div>
            </form>
        </div>
        <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
</div>