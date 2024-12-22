<?php
if ($status == '0') {
    $bgColor = 'success';
} else if ($status == '1') {
    $bgColor = 'info';
} else if ($status == '2') {
    $bgColor = 'danger';
} else if ($status == '3') {
    $bgColor = 'secondary';
} else {
    $bgColor = 'warning';
}
?>

<div class="div-tables">
    <div class="info-box bg-gradient-{{$bgColor}} table" 
    data-idtable="{{$id}}"
    data-status="{{$status}}"
    @if(Gate::allows('view-tables'))
    onclick="openOptionTable({{$id}})"
    @endif
    >
        <span class="info-box-icon">{{$id}}</i></span>
        @php
        $table_linked_id = $linked ? $linked : "0";
        @endphp
        @if($order)
        <input type="hidden" aria-hidden="true" id="total_price_{{$id}}" value = "{{$order->total_value}}_{{ $table_linked_id }}">
        @else
            @if($linked)
            <input type="hidden" aria-hidden="true" id="total_price_{{$id}}" value = "0_{{ $table_linked_id }}">
            @else
            <input type="hidden" aria-hidden="true" id="total_price_{{$id}}" value = "0_{{ $table_linked_id }}">
            @endif
        @endif
        <div class="info-box-content align-items-end">
            @if($linked)
            <span class="info-box-text"><i class="fas fa-link"></i> <span>{{$linked}}</span></span>
            @else
            <span class="info-box-text"><i class="fas fa-user"></i></span>
            @endif
            <span class="info-box-number">
                @if($linked)
                --
                @elseif($totalprice)
                {{$totalprice}}
                @else
                0.00
                @endif
            </span>
            <span class="progress-description">
                --
            </span>
        </div>
    </div>
</div>