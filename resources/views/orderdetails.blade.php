@extends('template')
@section('content')

<div class="row">
    <div class="col-md-12">
        <div class="row box-part text-center">
            <div class="col-md-12">
                <h2 class="mb-3">Estado de la Compra</h2>
            </div>
        </div>
        <div class="col-md-12">
            <div class="box-part text-center">
                <div style="font-size: 10rem">
                    @if($orders[0]->status == 'FAILED' || $orders[0]->status == 'REJECTED')
                        <i class="bi bi-x-circle text-danger"></i>
                    @elseif($orders[0]->status == 'CREATED' || $orders[0]->status == 'PENDING')
                        <i class="bi bi-exclamation-circle text-warning"></i>
                    @elseif($orders[0]->status == 'APPROVED')
                        <i class="bi bi-check2-circle text-success"></i>
                    @endif
                </div>
                <div class="title">
                    <h4>{{ $orders[0]->status }}</h4>
                </div>
                <div class="col-md-12 text-center">
                    <h6 class="card-title"><strong>Referencia:</strong> {{$orders[0]->reference}}</h6>
                </div>
                <div class="col-md-12 text-center">
                    <h6 class="card-title"><strong>Total:</strong> ${{number_format($orders[0]->total)}}</h6>
                </div>
                <div class="col-md-12 text-center">
                    <h6 class="card-title"><strong>Fecha transacción:</strong>
                        {{ date('d-m-Y', strtotime($orders[0]->created_at))}}
                </div>
                @if($orders[0]->status == 'REJECTED')
                    <a href="{{$orders[0]->url}}">Intentar el pago nuevamente</a>
                @endif
                @if ($orders[0]->status == 'PENDING')
                    <a href="{{$orders[0]->url}}">Verificar Pago</a>
                    &nbsp;&nbsp;&nbsp;|
                @endif
                <a href="{{ route('order-list') }}">&nbsp;&nbsp;&nbsp;Volver al Listado</a>
             </div>
        </div>
    </div>
</div>

@endsection
