<x-layout>
  <script src="{{asset('src/js/telefone.js')}}"></script>
  <script src="{{asset('src/js/funcoes.js')}}"></script>

  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <div class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1 class="m-0">Mesas</h1>
          </div><!-- /.col -->
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="{{ route('gestao')}}">Home</a></li>
              <li class="breadcrumb-item active">Mesas</li>
            </ol>
          </div><!-- /.col -->
        </div><!-- /.row -->
      </div><!-- /.container-fluid -->
    </div>
    <!-- /.content-header -->

    <!-- Main content -->
    <div class="content">
      <div class="container-fluid">
        <div class="row">
          <div class="col-lg-12">
            <div class="card">
              @if (session('success'))
              <div class="alert alert-success alert-dismissible fade show" role="alert">
                {{ session('success') }}
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                  <span aria-hidden="true">&times;</span>
                </button>
              </div>
              @endif

              <div class="card-header border-0">
                <!-- <div class="d-flex justify-content-between"> -->
                <div class="d-flex">

                  <div class="content_caption">
                    <x-caption caption="Liberada:" bgCaption="success" />
                    <x-caption caption="Aberta:" bgCaption="info" />
                    <x-caption caption="Fechada:" bgCaption="danger" />
                    <x-caption caption="Reservada:" bgCaption="secondary" />
                    <x-caption caption="Inativa:" bgCaption="warning" />
                  </div>
                </div>
                <div class="d-flex justify-content-end" style="padding-right: 30px;">
                  @if (Gate::allows('linked-tables-option'))
                  <button class="btn btn-warning mr-2" id="btn-selected-tables-linked" onclick="enableSelectTableMode()">Juntar Mesas</button>
                  <button id="btn-linked-tables" style="display: none;" type="button" class="btn btn-success" data-toggle="modal" data-target="#modal-principal-table">
                    Juntar
                  </button>
                  {{-- <button id="btn-reservation" type="button" class="btn btn-primary" onclick="resetModal()" data-toggle="modal" data-target="#modal-reservation">
                    Reservar
                  </button> --}}
                  @endif
                </div>
              </div>
              <div class="card-body">
                @if ($tables)
                <div class="row">
                  @foreach ($tables as $table)
                  <x-mesa :id="$table->id" :status="$table->status" :linked="$table->linked_table_id" :order="$table->openOrder" :totalprice="$table->totalPrice" />
                  @endforeach
                </div>
                @endif
              </div>
            </div>
          </div>
        </div>
      </div>

      <x-small_modal id="modal-reservation" titleModal="Reserva" textBtn="Continuar">
        <div class="modal-body">
          <form id="telefoneForm" method="POST" action="{{route('find-client-cel')}}">
            @csrf
            <div class="card-body">
              <div class="form-group">
                <label for="telefone">Telefone/Celular</label>
                <input type="text" class="form-control" id="telefone" name="telefone" placeholder="(xx) xxxxx-xxxx" maxlength="15" oninput="mascaraTelefone(this)">
              </div>
            </div>
          </form>
        </div>
      </x-small_modal>

      <x-small_modal id="modal-principal-table" titleModal="PrincipalTable" textBtn="Continuar">
        <div class="modal-body">
          <form id="PrincipalTableForm" method="POST" action="{{route('link-tables')}}">
            @csrf
            <div class="card-body">
              <div class="form-group">
                <label for="table_principal">Mesa principal</label>
                <input type="number" class="form-control" id="table_principal" name="table_principal" placeholder="Digite o número da mesa principal">
              </div>
            </div>
          </form>
        </div>
      </x-small_modal>

      <x-medium_modal />

      <x-options_table />

      <x-modal_products_options />

      <x-modal-transferencia />
    </div>
    <!-- /.content -->
  </div>
  </div>
  <script src="{{asset('src/js/tables/funcoes.js')}}"></script>

</x-layout>