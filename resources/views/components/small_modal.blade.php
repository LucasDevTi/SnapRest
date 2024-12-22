<div class="modal fade" id="{{ $id }}">
    <div class="modal-dialog {{ $id }}">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">{{ $titleModal }}</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>

            <!-- Alerta de sucesso -->
            <div class="alert alert-success alert-success-small-modal">
               
            </div>

            <!-- Alerta de erro -->
            <div class="alert alert-danger alert-error-small-modal">
                
            </div>

            <div class="overlay-wrapper">
                <div class="overlay d-none">
                    <i class="fas fa-2x fa-sync-alt fa-spin"></i>
                </div>
                {{ $slot }}
            </div>

            <div class="modal-footer justify-content-between">
                <button type="button" class="btn btn-default" data-dismiss="modal">Fechar</button>
                @if ($titleModal === 'Reserva')
                <button type="button" class="btn btn-primary" onclick="buscaCliente()">{{ $textBtn }}</button>
                @elseif ($titleModal === 'PrincipalTable')
                <button type="button" class="btn btn-primary" onclick="linkedTables()">{{ $textBtn }}</button>
                @else
                <button type="button" class="btn btn-primary">{{ $textBtn }}</button>
                @endif
            </div>
        </div>
        <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
</div>