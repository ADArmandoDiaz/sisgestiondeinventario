@extends('adminlte::page')

@section('content_header')
    <nav aria-label="breadcrumb" style="font-size: 15pt">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ url('/admin') }}">Inicio</a></li>
            <li class="breadcrumb-item"><a href="{{ url('/admin/lotes') }}">Lotes</a></li>
            <li class="breadcrumb-item active" aria-current="page">Creación de lote</li>
        </ol>
    </nav>
    <hr>
@stop

@section('content')
    <div class="row">
        <div class="col-md-6">
            <div class="card card-primary">
                <div class="card-header">
                    <h3 class="card-title">Llene los datos del formulario</h3>
                </div>
                <div class="card-body">
                    <form action="{{ route('lotes.store') }}" method="POST">
                        @csrf
                        <div class="form-group">
                            <label for="producto_id">Producto <b>(*)</b></label>
                            <select name="producto_id" id="producto_id" class="form-control" required>
                                <option value="">-- Seleccione un producto --</option>
                                @foreach ($productos as $producto)
                                    <option value="{{ $producto->id }}"
                                        {{ old('producto_id') == $producto->id ? 'selected' : '' }}>
                                        {{ $producto->nombre }}
                                    </option>
                                @endforeach
                            </select>
                            @error('producto_id')
                                <small style="color: red">{{ $message }}</small>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label for="proveedor_id">Proveedor <b>(*)</b></label>
                            <select name="proveedor_id" id="proveedor_id" class="form-control" required>
                                <option value="">-- Seleccione un proveedor --</option>
                                @foreach ($proveedores as $proveedor)
                                    <option value="{{ $proveedor->id }}"
                                        {{ old('proveedor_id') == $proveedor->id ? 'selected' : '' }}>
                                        {{ $proveedor->nombre_empresa }}
                                    </option>
                                @endforeach
                            </select>
                            @error('proveedor_id')
                                <small style="color: red">{{ $message }}</small>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label for="codigo_lote">Código de lote</label>
                            <input type="text" name="codigo_lote" id="codigo_lote" class="form-control"
                                value="{{ old('codigo_lote') }}" placeholder="Ingrese código del lote">
                            @error('codigo_lote')
                                <small style="color: red">{{ $message }}</small>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label for="cantidad_actual">Cantidad <b>(*)</b></label>
                            <input type="number" name="cantidad_actual" id="cantidad_actual" class="form-control"
                                value="{{ old('cantidad_actual') }}" min="0" required>
                            @error('cantidad_actual')
                                <small style="color: red">{{ $message }}</small>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label for="fecha_ingreso">Fecha de ingreso <b>(*)</b></label>
                            <input type="date" name="fecha_ingreso" id="fecha_ingreso" class="form-control"
                                value="{{ old('fecha_ingreso') }}" required>
                            @error('fecha_ingreso')
                                <small style="color: red">{{ $message }}</small>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label for="fecha_vencimiento">Fecha de vencimiento</label>
                            <input type="date" name="fecha_vencimiento" id="fecha_vencimiento" class="form-control"
                                value="{{ old('fecha_vencimiento') }}">
                            @error('fecha_vencimiento')
                                <small style="color: red">{{ $message }}</small>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label for="estado">Estado <b>(*)</b></label>
                            <select name="estado" id="estado" class="form-control" required>
                                <option value="1" {{ old('estado') == '1' ? 'selected' : '' }}>Activo</option>
                                <option value="0" {{ old('estado') == '0' ? 'selected' : '' }}>Inactivo</option>
                            </select>
                            @error('estado')
                                <small style="color: red">{{ $message }}</small>
                            @enderror
                        </div>

                        <hr>
                        <div class="form-group">
                            <a href="{{ route('lotes.index') }}" class="btn btn-default">Cancelar</a>
                            <button type="submit" class="btn btn-primary">Guardar</button>
                        </div>

                    </form>
                </div>
            </div>
        </div>
    </div>
@stop
