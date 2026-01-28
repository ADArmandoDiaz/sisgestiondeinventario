@extends('adminlte::page')

@section('title', 'Movimiento #' . $movimiento->id)

@section('content_header')
    <nav aria-label="breadcrumb" style="font-size: 15pt">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ url('/admin') }}">Inicio</a></li>
            <li class="breadcrumb-item"><a href="{{ url('/admin/movimientos') }}">Movimientos</a></li>
            <li class="breadcrumb-item active" aria-current="page">Movimiento #{{ $movimiento->id }}</li>
        </ol>
    </nav>
    <hr>
@stop

@section('content')
    <div class="row">
        <div class="col-md-6">
            <div class="card card-info">
                <div class="card-header">
                    <h3 class="card-title">Datos del Movimiento</h3>
                </div>
                <div class="card-body" style="display: block;">

                    <div class="form-group">
                        <label for="producto">Producto</label>
                        <input type="text" class="form-control" id="producto"
                            value="{{ $movimiento->producto->nombre }}" readonly>
                    </div>

                    <div class="form-group">
                        <label for="tipo">Tipo</label>
                        <input type="text" class="form-control" id="tipo" value="{{ ucfirst($movimiento->tipo) }}"
                            readonly>
                    </div>

                    <div class="form-group">
                        <label for="cantidad">Cantidad</label>
                        <input type="text" class="form-control" id="cantidad" value="{{ $movimiento->cantidad }}"
                            readonly>
                    </div>

                    <div class="form-group">
                        <label for="usuario">Usuario ID</label>
                        <input type="text" class="form-control" id="usuario" value="{{ $movimiento->usuario_id }}"
                            readonly>
                    </div>

                    <div class="form-group">
                        <label for="descripcion">Descripción</label>
                        <textarea class="form-control" id="descripcion" rows="3" readonly>{{ $movimiento->descripcion ?? 'N/A' }}</textarea>
                    </div>

                    <div class="form-group">
                        <label for="fecha">Fecha</label>
                        <input type="text" class="form-control" id="fecha"
                            value="{{ $movimiento->created_at->format('d/m/Y H:i') }}" readonly>
                    </div>

                    <hr>

                    <div class="form-group">
                        <a href="{{ url('admin/movimientos') }}" class="btn btn-default">Volver</a>
                    </div>

                </div>
            </div>
        </div>
    </div>
@stop

@section('css')
@stop

@section('js')
@stop
