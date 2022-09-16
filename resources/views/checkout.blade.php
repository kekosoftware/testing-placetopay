@extends('template')
@section('content')

<div class="row">
    <div class="col-md-12">
        <div class="row">
            <div class="col-md-10">
                <h2 class="mb-3">CREAR ORDEN</h2>
            </div>
        </div>

        @if ($errors->any())
            <div class="alert alert-danger">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif
        <div class="container mt-5">
            <form class="needs-validation" action="{{ route('order.store') }}" method="POST">
                @csrf
                <input type="hidden" name='product' value="{{ $product->id }}">
                <div class="form-row">
                    <H5 for="inputname">DATOS PERSONALES</H5>
                </div>
                <div class="form-row">
                    <div class="form-group col-md-5">
                        <label for="name">Nombre:</label>
                        <input type="text" class="form-control" name="name" placeholder="Ingrese su nombre"  required>
                    </div>
                    <div class="form-group col-md-4">
                        <label for="mail">Email</label>
                        <input type="email" class="form-control" name="email" placeholder="Email" required>
                    </div>
                    <div class="form-group col-md-3">
                        <label for="mobile">Telefono:</label>
                        <input type="number" class="form-control" name="mobile" placeholder="+54 999 999-8888" required>
                    </div>
                </div>
                <div class="form-row">&nbsp;</div>
                <div class="form-row">
                    <H5 for="inputname">DATOS DE REFERENCIA</H5>
                </div>
                <div class="form-row">
                    <div class="form-group col-md-7">
                        <label for="inputProduct">Producto:</label>
                        <input type="text" class="form-control" name="inputProduct" placeholder="Product Name" value="{{ $product->name }}" disabled>
                    </div>
                    <div class="form-group col-md-5">
                        <label for="inputPrice">Precio ($):</label>
                        <input type="text" class="form-control" name="inputPrice" placeholder="Price" value="{{ $product->price }}" disabled>
                    </div>
                </div>

                <button type="submit" class="btn btn-primary">PROCESAR LA ORDEN</button>
              </form>
        </div>
    </div>
</div>

@endsection
