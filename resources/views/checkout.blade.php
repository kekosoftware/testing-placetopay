@extends('template')
@section('content')

<div class="row">
    <div class="col-md-12">
        <div class="row">
            <div class="col-md-10">
                <h2 class="mb-3">CHECKOUT</h2>
            </div>
        </div>
        <div class="row">
            <div class="col-md-12">
                <h4 class="mb-3">Producto: {{ $buy->name }}</h4>
            </div>
        </div>

        <div class="container mt-5">
            <form>
                <div class="form-row">
                    <H5 for="inputname">DATOS PERSONALES</H5>
                </div>
                <div class="form-row">
                    <div class="form-group col-md-5">
                        <label for="inputname">Nombre:</label>
                        <input type="text" class="form-control" name="inputname" placeholder="Ingrese su nombre">
                    </div>
                    <div class="form-group col-md-4">
                        <label for="inputEmail">Email</label>
                        <input type="email" class="form-control" name="inputEmail" placeholder="Email">
                    </div>
                    <div class="form-group col-md-3">
                        <label for="inputPhone">Telefono:</label>
                        <input type="number" class="form-control" name="inputPhone" placeholder="00" required>
                    </div>
                </div>
                <div class="form-row">&nbsp;</div>
                <div class="form-row">
                    <H5 for="inputname">DATOS DE REFERENCIA</H5>
                </div>
                <div class="form-row">
                    <div class="form-group col-md-5">
                        <label for="inputname">Nombre:</label>
                        <input type="text" class="form-control" name="inputname" placeholder="Ingrese su nombre">
                    </div>
                    <div class="form-group col-md-4">
                        <label for="inputEmail">Email</label>
                        <input type="email" class="form-control" name="inputEmail" placeholder="Email">
                    </div>
                    <div class="form-group col-md-3">
                        <label for="inputPhone">Telefono:</label>
                        <input type="number" class="form-control" name="inputPhone" placeholder="00" required>
                    </div>
                </div>

                <button type="submit" class="btn btn-primary">Sign in</button>
              </form>
        </div>
    </div>
</div>

@endsection
