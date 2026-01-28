@extends('adminlte::page')

{{-- @section('title', 'Sucursales') --}}

@section('content_header')
    <nav aria-label="breadcrumb" style="font-size: 15pt">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ url('/admin') }}">Inicio</a></li>
            <li class="breadcrumb-item"><a href="{{ url('/admin/compras') }}">Compras</a></li>
            <li class="breadcrumb-item active" aria-current="page">Compra N° {{ $compra->id }} </li>
        </ol>
    </nav>
    <hr>
@stop

@section('content')
    <div class="row">
        <div class="col-md-12">
            <div class="card card-info">
                <div class="card-header">
                    <h3 class="card-title">Paso N° 1 | Compra creada</h3>


                    <!-- /.card-tools -->
                </div>
                <!-- /.card-header -->
                <div class="card-body" style="display: block;">


                    <div class="row">
                        <div class="col-md-2">
                            <div class="form-group">
                                <label for="nombre">Proveedores</label>
                                <p>{{ $compra->proveedor->contacto_nombre }}</p>
                            </div>
                        </div>
                        <div class="col-md-2">
                            <div class="form-group">
                                <label for="fecha_compra">Fecha </label>
                                <p>{{ $compra->fecha_compra }}</p>

                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="observacion">Observaciones </label>
                                <p>{{ $compra->observacion }}</p>

                            </div>
                        </div>
                        <div class="col-md-2">
                            <div class="form-group">
                                <label for="estado">Estado de la compra </label>
                                <p>{{ $compra->estado }}</p>

                            </div>
                        </div>
                        <div class="col-md-2">
                            <div class="form-group">
                                <label for="total">Total </label>
                                <p>{{ $compra->total }}</p>

                            </div>
                        </div>
                    </div>


                </div>
                <!-- /.card-body -->
            </div>
            <!-- /.card -->
        </div>
    </div>
    <div class="row">
        <div class="col-md-12">
            <div class="card card-primary">
                <div class="card-header">
                    <h3 class="card-title">Paso N° 2 | Agregar productos</h3>


                    <!-- /.card-tools -->
                </div>
                <!-- /.card-header -->
                <div class="card-body" style="display: block;">

                    <livewire:counter></livewire:counter>

                </div>
                <!-- /.card-body -->
            </div>
            <!-- /.card -->
        </div>
    </div>
@stop

@section('css')
    @livewireStyles

@stop

@section('js')
    @livewireScripts

@stop
