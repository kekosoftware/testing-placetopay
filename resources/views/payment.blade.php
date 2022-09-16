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
                    @if($result->status->status == 'FAILED' || $result->status->status == 'REJECTED')
                        <i class="bi bi-x-circle text-danger"></i>
                    @elseif($result->status->status == 'CREATED' || $result->status->status == 'PENDING')
                        <i class="bi bi-exclamation-circle text-warning"></i>
                    @elseif($result->status->status == 'APPROVED')
                        <i class="bi bi-check2-circle text-success"></i>
                    @endif
                </div>
                <div class="title">
                    <h4>{{ $result->status->status }}</h4>
                </div>
                <div class="col-md-12 text-center confir-block-descrip">

                    <h6 class="card-title"><strong>Estado:</strong> {{$result->status->message}}</h6>
                </div>
                <div class="col-md-12 text-center">
                    <h6 class="card-title"><strong>Referencia:</strong> {{$transac->reference}}</h6>
                </div>
                <div class="col-md-12 text-center">
                    <h6 class="card-title"><strong>Total:</strong> ${{number_format($order->total)}}</h6>
                </div>
                <div class="col-md-12 text-center">
                    <h6 class="card-title"><strong>Fecha transacción:</strong>
                        {{ date('d-m-Y', strtotime($transac->created_at))}}
                </div>
                @if($result->status->status == 'REJECTED')
                    <a href="{{$transac->url}}">Intentar el pago nuevamente</a>
                @endif
                @if ($result->status->status == 'PENDING')
                    <a href="{{$transac->url}}">Verificar Pago</a>
                @endif
                &nbsp;&nbsp;&nbsp;|
                <a href="{{ route('home') }}">&nbsp;&nbsp;&nbsp;Volver al inicio</a>
             </div>
        </div>
    </div>
</div>

@endsection
