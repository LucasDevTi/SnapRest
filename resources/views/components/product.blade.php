<div class="div-products col-sm-6" id="products-list" onclick="showModalProdEdit('{{$name}}', '{{$ingredients}}', '{{$price}}', '{{$id}}')">
    <div class="div-item-prod">
        <div class="desc-product">
            <h2>{{$name}}</h2>
            <span class="ingredients">{{$ingredients}}</span>
        </div>
        <div class="price-product">
            R$ {{$price}}
        </div>
    </div>
    <div class="div-trash-product" onclick="excluirProduto(event, '{{$id}}')">
        <i class="fa fa-trash" aria-hidden="true"></i>
    </div>
</div>