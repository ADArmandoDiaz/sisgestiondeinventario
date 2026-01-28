@extends('adminlte::page')

@section('content_header')
    <nav aria-label="breadcrumb" style="font-size: 15pt">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ url('/admin') }}">Inicio</a></li>
            <li class="breadcrumb-item"><a href="{{ url('/admin/movimientos') }}">Movimientos</a></li>
            <li class="breadcrumb-item active" aria-current="page">Editar movimiento</li>
        </ol>
    </nav>
    <hr>
@stop

@section('content')
    <div class="row">
        <div class="col-md-6">
            <div class="card card-warning">
                <div class="card-header">
                    <h3 class="card-title">Editar Movimiento</h3>
                </div>
                <div class="card-body" style="display: block;">
                    <form action="{{ url('/admin/movimientos/' . $movimiento->id) }}" method="POST">
                        @csrf
                        @method('PUT')

                        {{-- Producto --}}
                        <div class="form-group">
                            <label for="producto_id">Producto <b>(*)</b></label>
                            <div class="input-group mb-3">
                                <div class="input-group-prepend">
                                    <span class="input-group-text"><i class="fas fa-box"></i></span>
                                </div>
                                <select name="producto_id" id="producto_id" class="form-control" required>
                                    <option value="">-- Seleccione --</option>
                                    @foreach ($productos as $producto)
                                        <option value="{{ $producto->id }}"
                                            {{ old('producto_id', $movimiento->producto_id) == $producto->id ? 'selected' : '' }}>
                                            {{ $producto->nombre }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            @error('producto_id')
                                <small style="color: red">{{ $message }}</small>
                            @enderror
                        </div>

                        {{-- Tipo --}}
                        <div class="form-group">
                            <label for="tipo">Tipo <b>(*)</b></label>
                            <div class="input-group mb-3">
                                <div class="input-group-prepend">
                                    <span class="input-group-text"><i class="fas fa-exchange-alt"></i></span>
                                </div>
                                <select name="tipo" id="tipo" class="form-control" required>
                                    <option value="">-- Seleccione --</option>
                                    <option value="entrada"
                                        {{ old('tipo', $movimiento->tipo) == 'entrada' ? 'selected' : '' }}>Entrada</option>
                                    <option value="salida"
                                        {{ old('tipo', $movimiento->tipo) == 'salida' ? 'selected' : '' }}>Salida</option>
                                </select>
                            </div>
                            @error('tipo')
                                <small style="color: red">{{ $message }}</small>
                            @enderror
                        </div>

                        {{-- Lote --}}
                        <div class="form-group">
                            <label for="lote_id">Lote (opcional)</label>
                            <select name="lote_id" id="lote_id" class="form-control">
                                <option value="">-- Seleccione un lote o se creará uno nuevo --</option>
                                @foreach ($productos as $producto)
                                    @foreach ($producto->lotes as $lote)
                                        <option value="{{ $lote->id }}" data-producto="{{ $producto->id }}"
                                            {{ old('lote_id', $movimiento->lote_id) == $lote->id ? 'selected' : '' }}>
                                            {{ $lote->codigo_lote }} (Producto: {{ $producto->nombre }})
                                        </option>
                                    @endforeach
                                @endforeach
                            </select>
                            @error('lote_id')
                                <small style="color: red">{{ $message }}</small>
                            @enderror
                        </div>

                        {{-- Campos para crear un nuevo lote --}}
                        <div id="datos_lote_nuevo" style="display: none;">
                            {{-- Proveedor --}}
                            <div class="form-group">
                                <label for="proveedor_id">Proveedor</label>
                                <div class="input-group mb-3">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text"><i class="fas fa-truck"></i></span>
                                    </div>
                                    <select name="proveedor_id" id="proveedor_id" class="form-control">
                                        <option value="">-- Seleccione proveedor --</option>
                                        @foreach ($proveedores as $p)
                                            <option value="{{ $p->id }}"
                                                {{ old('proveedor_id', $movimiento->proveedor_id) == $p->id ? 'selected' : '' }}>
                                                {{ $p->nombre_empresa }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                                @error('proveedor_id')
                                    <small style="color: red">{{ $message }}</small>
                                @enderror
                            </div>

                            {{-- Fecha de vencimiento --}}
                            <div class="form-group">
                                <label for="fecha_vencimiento">Fecha de vencimiento (opcional)</label>
                                <div class="input-group mb-3">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text"><i class="fas fa-calendar"></i></span>
                                    </div>
                                    <input type="date" name="fecha_vencimiento" id="fecha_vencimiento"
                                        class="form-control"
                                        value="{{ old('fecha_vencimiento', $movimiento->lote ? $movimiento->lote->fecha_vencimiento : '') }}">
                                </div>
                                @error('fecha_vencimiento')
                                    <small style="color: red">{{ $message }}</small>
                                @enderror
                            </div>
                        </div>

                        {{-- Cantidad --}}
                        <div class="form-group">
                            <label for="cantidad">Cantidad <b>(*)</b></label>
                            <div class="input-group mb-3">
                                <div class="input-group-prepend">
                                    <span class="input-group-text"><i class="fas fa-hashtag"></i></span>
                                </div>
                                <input type="number" name="cantidad" id="cantidad" class="form-control" min="1"
                                    value="{{ old('cantidad', $movimiento->cantidad) }}" required>
                            </div>
                            @error('cantidad')
                                <small style="color: red">{{ $message }}</small>
                            @enderror
                        </div>

                        {{-- Descripción --}}
                        <div class="form-group">
                            <label for="descripcion">Descripción (opcional)</label>
                            <textarea name="descripcion" id="descripcion" rows="3" class="form-control">{{ old('descripcion', $movimiento->descripcion) }}</textarea>
                        </div>

                        <hr>
                        <div class="form-group d-flex justify-content-between">
                            <a href="{{ url('/admin/movimientos/') }}" class="btn btn-default">Cancelar</a>
                            <button type="submit" class="btn btn-warning">Actualizar</button>
                        </div>

                    </form>
                </div>
            </div>
        </div>
    </div>
@stop

@section('css')
@stop

@section('js')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const tipo = document.getElementById('tipo');
            const loteSelect = document.getElementById('lote_id');
            const datosNuevoLote = document.getElementById('datos_lote_nuevo');
            const proveedorSelect = document.getElementById('proveedor_id');
            const productoSelect = document.getElementById('producto_id');

            function actualizarCampos() {
                const esEntrada = tipo.value === 'entrada';
                const loteSeleccionado = loteSelect.value !== "";

                // Mostrar campos de nuevo lote solo si es entrada y no hay lote seleccionado
                if (esEntrada && !loteSeleccionado) {
                    datosNuevoLote.style.display = 'block';
                    proveedorSelect.setAttribute('required', 'required');
                } else {
                    datosNuevoLote.style.display = 'none';
                    proveedorSelect.removeAttribute('required');
                }

                // Filtrar lotes por producto seleccionado
                const productoId = productoSelect.value;
                Array.from(loteSelect.options).forEach(option => {
                    if (option.value === "") return; // opción vacía siempre visible
                    option.style.display = option.dataset.producto === productoId ? 'block' : 'none';
                });
            }

            tipo.addEventListener('change', actualizarCampos);
            loteSelect.addEventListener('change', actualizarCampos);
            productoSelect.addEventListener('change', actualizarCampos);

            actualizarCampos(); // Inicial
        });
    </script>
@stop
