@extends('template')
@section('content')

<div class="row">
    <div class="col-md-12">
        <div class="row">
            <div class="col-md-10">
                <h2 class="mb-3">Listado de productos</h2>
            </div>
            <div class="col-md-2">
                <a class="btn btn-outline-success"
                    href="{{ route('checkout',2) }}">
                    Listado de Ordenes
                </a>
            </div>
        </div>

        <div class="container mt-5">
            <table class="table table-bordered mb-5">
                <thead>
                    <tr class="table-success text-center">
                        <th scope="col">#</th>
                        <th scope="col">Nombre</th>
                        <th scope="col">Precio</th>
                        <th scope="col">Acción</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($products as $data)
                    <tr>
                        <th scope="row" class="text-center">{{ $data->id }}</th>
                        <td>{{ $data->name }}</td>
                        <td class="text-center">${{number_format($data->price)}}</td>
                        <td class="text-center">
                            <a class="btn btn-outline-primary"
                                href="{{ route('checkout', $data->id) }}">
                                Comprar
                            </a>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
            {{-- Pagination --}}
            <div class="d-flex justify-content-center">
                {!! $products->links() !!}
            </div>
        </div>
    </div>
</div>

@endsection
