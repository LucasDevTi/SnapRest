<x-layout>
    <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <div class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1 class="m-0">Produtos</h1>
                    </div><!-- /.col -->
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-right">
                            <li class="breadcrumb-item"><a href="{{ route('gestao')}}">Home</a></li>
                            <li class="breadcrumb-item active">Produtos</li>
                        </ol>
                    </div><!-- /.col -->
                </div><!-- /.row -->
            </div><!-- /.container-fluid -->
        </div>

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
                                <div class="search-container">
                                    <input type="text" id="search-input" class="form-control" placeholder="Pesquisar produtos...">
                                </div>
                                <div class="d-flex justify-content-end" style="padding-right: 30px;">
                                    @if (auth()->user()->user_type == 1)
                                    <button id="btn-reservation" type="button" class="btn btn-primary" data-toggle="modal" data-target="#cad-produtos-modal">
                                        Cadastrar
                                    </button>
                                    @endif
                                </div>
                            </div>
                            <div class="card-body">
                                @foreach ($products as $product)
                                <x-product :id="$product->id" :name="$product->name" :price="$product->price" :ingredients="$product->ingredients" />
                                @endforeach
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <x-cad_produtos_modal />
        </div>
    </div>

    <script src="{{asset('src/js/produtos/funcoes.js')}}"></script>

</x-layout>