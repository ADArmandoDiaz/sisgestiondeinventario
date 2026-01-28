@extends('adminlte::page')

{{-- @section('title', 'Lotes') --}}

@section('content_header')
    <nav aria-label="breadcrumb" style="font-size: 15pt">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ url('/admin') }}">Inicio</a></li>
            <li class="breadcrumb-item"><a href="{{ url('/admin/lotes') }}">Lotes</a></li>
            <li class="breadcrumb-item active" aria-current="page">Datos del lote</li>
        </ol>
    </nav>
    <hr>
@stop

@section('content')
    <div class="row">
        <div class="col-md-6">
            <div class="card card-info">
                <div class="card-header">
                    <h3 class="card-title">Datos del lote</h3>
                </div>
                <div class="card-body" style="display: block;">

                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group">
                                <label for="producto">Producto</label>
                                <input type="text" class="form-control" id="producto"
                                    value="{{ $lote->producto->nombre }}" readonly>
                            </div>
                        </div>

                        <div class="col-md-12">
                            <div class="form-group">
                                <label for="proveedor">Proveedor</label>
                                <input type="text" class="form-control" id="proveedor"
                                    value="{{ $lote->proveedor->nombre_empresa }}" readonly>
                            </div>
                        </div>

                        <div class="col-md-12">
                            <div class="form-group">
                                <label for="codigo_lote">Código del lote</label>
                                <input type="text" class="form-control" id="codigo_lote" value="{{ $lote->codigo_lote }}"
                                    readonly>
                            </div>
                        </div>

                        <div class="col-md-12">
                            <div class="form-group">
                                <label for="cantidad_actual">Cantidad actual</label>
                                <input type="number" class="form-control" id="cantidad_actual"
                                    value="{{ $lote->cantidad_actual }}" readonly>
                            </div>
                        </div>

                        <div class="col-md-12">
                            <div class="form-group">
                                <label for="fecha_ingreso">Fecha de ingreso</label>
                                <input type="date" class="form-control" id="fecha_ingreso"
                                    value="{{ \Carbon\Carbon::parse($lote->fecha_ingreso)->format('Y-m-d') }}" readonly>
                            </div>
                        </div>

                        <div class="col-md-12">
                            <div class="form-group">
                                <label for="fecha_vencimiento">Fecha de vencimiento</label>
                                <input type="date" class="form-control" id="fecha_vencimiento"
                                    value="{{ $lote->fecha_vencimiento ? \Carbon\Carbon::parse($lote->fecha_vencimiento)->format('Y-m-d') : '' }}"
                                    readonly>
                            </div>
                        </div>

                        <div class="col-md-12">
                            <div class="form-group">
                                <label for="estado">Estado</label>
                                <input type="text" class="form-control" id="estado"
                                    value="{{ $lote->estado ? 'Activo' : 'Inactivo' }}" readonly>
                            </div>
                        </div>
                    </div>

                    <hr>
                    <div class="row">
                        <div class="col-md-12">
                            <a href="{{ url('admin/lotes') }}" class="btn btn-default">Volver</a>
                        </div>
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
