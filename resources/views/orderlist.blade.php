@extends('template')
@section('content')

<div class="row">
    <div class="col-md-12">
        <div class="row">
            <div class="col-md-10">
                <h2 class="mb-3">Listado de Ordenes</h2>
            </div>
            <div class="col-md-2">
                <a class="btn btn-outline-success"
                    href="{{ route('home') }}">
                    Volver al Inicio
                </a>
            </div>
        </div>

        <div class="container mt-5">
            <table class="table table-bordered mb-5">
                <thead>
                    <tr class="table-success text-center">
                        <th scope="col">#</th>
                        <th scope="col">Nombre</th>
                        <th scope="col">Email</th>
                        <th scope="col">Telefono</th>
                        <th scope="col">Producto</th>
                        <th scope="col">Estado</th>
                        <th scope="col">Total</th>
                        <th scope="col">Acción</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($orders as $data)
                    <tr>
                        <th scope="row" class="text-center">{{ $data->id }}</th>
                        <td>{{ $data->customer_name }}</td>
                        <td>{{ $data->customer_email }}</td>
                        <td>{{ $data->customer_mobile }}</td>
                        <td>{{ $data->name }}</td>
                        <td style="text-align: center">
                            @if($data->status == 'FAILED' || $data->status == 'REJECTED')
                                <span class="badge badge-danger">
                                    {{ $data->status }}
                                </span>
                            @elseif($data->status == 'CREATED' || $data->status == 'PENDING')
                                <span class="badge badge-warning">
                                    {{ $data->status }}
                                </span>
                            @elseif($data->status == 'APPROVED')
                                <span class="badge badge-success">
                                    {{ $data->status }}
                                </span>
                            @endif
                        </td>
                        <td class="text-center">${{number_format($data->total)}}</td>
                        <td class="text-center">
                            <a class="btn btn-outline-primary"
                                href="{{ route('order-details', $data->order_id) }}">
                                Detalles
                            </a>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
            {{-- Pagination --}}
            <div class="d-flex justify-content-center">
                {!! $orders->links() !!}
            </div>
        </div>
    </div>
</div>

@endsection
