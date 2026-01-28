    @extends('adminlte::page')

    @section('content_header')
        <nav aria-label="breadcrumb" style="font-size: 15pt">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ url('/admin') }}">Inicio</a></li>
                <li class="breadcrumb-item"><a href="{{ url('/admin/movimientos') }}">Movimientos</a></li>
                <li class="breadcrumb-item active" aria-current="page">Registrar movimiento</li>
            </ol>
        </nav>
        <hr>
    @stop

    @section('content')
        <div class="row">
            <div class="col-md-6">
                <div class="card card-primary">
                    <div class="card-header">
                        <h3 class="card-title">Complete los datos del movimiento</h3>
                    </div>
                    <div class="card-body" style="display: block;">
                        <form action="{{ url('/admin/movimientos/') }}" method="POST">
                            @csrf
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
                                                {{ old('producto_id') == $producto->id ? 'selected' : '' }}>
                                                {{ $producto->nombre }}
                                            </option>
                                        @endforeach
                                    </select>
                                    {{-- Campos para crear un lote nuevo (solo entrada) --}}
                                </div>
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
                                                    <option value="{{ $p->id }}">{{ $p->nombre_empresa }}
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
                                                class="form-control">
                                        </div>
                                        @error('fecha_vencimiento')
                                            <small style="color: red">{{ $message }}</small>
                                        @enderror
                                    </div>

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
                                        <option value="entrada" {{ old('tipo') == 'entrada' ? 'selected' : '' }}>Entrada
                                        </option>
                                        <option value="salida" {{ old('tipo') == 'salida' ? 'selected' : '' }}>Salida
                                        </option>
                                    </select>
                                </div>
                                @error('tipo')
                                    <small style="color: red">{{ $message }}</small>
                                @enderror
                            </div>


                            {{-- Cantidad --}}
                            <div class="form-group">
                                <label for="cantidad">Cantidad <b>(*)</b></label>
                                <div class="input-group mb-3">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text"><i class="fas fa-hashtag"></i></span>
                                    </div>
                                    <input type="number" name="cantidad" id="cantidad" class="form-control" min="1"
                                        value="{{ old('cantidad') }}" required>
                                </div>
                                @error('cantidad')
                                    <small style="color: red">{{ $message }}</small>
                                @enderror
                            </div>

                            {{-- Lote --}}
                            {{-- <div class="form-group">
                                <label for="lote_id">Lote (opcional)</label>
                                <div class="input-group mb-3">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text"><i class="fas fa-layer-group"></i></span>
                                    </div>
                                    <select name="lote_id" id="lote_id" class="form-control">
                                        <option value="">-- Seleccione un lote o se creará uno nuevo --</option>
                                       
                                    </select>
                                </div>
                                @error('lote_id')
                                    <small style="color: red">{{ $message }}</small>
                                @enderror
                            </div> --}}
                            {{-- Lote --}}
                            <div class="form-group">
                                <label for="lote_id">Lote (opcional)</label>
                                <select name="lote_id" id="lote_id" class="form-control">
                                    <option value="">-- Seleccione un lote o se creará uno nuevo --</option>
                                    @foreach ($productos as $producto)
                                        @foreach ($producto->lotes as $lote)
                                            <option value="{{ $lote->id }}" data-producto="{{ $producto->id }}">
                                                {{ $lote->codigo_lote }} (Producto: {{ $producto->nombre }})
                                            </option>
                                        @endforeach
                                    @endforeach
                                </select>
                                @error('lote_id')
                                    <small style="color: red">{{ $message }}</small>
                                @enderror
                            </div>

                            {{-- Descripción --}}
                            <div class="form-group">
                                <label for="descripcion">Descripción (opcional)</label>
                                <textarea name="descripcion" id="descripcion" rows="3" class="form-control" placeholder="Ingrese una descripción">{{ old('descripcion') }}</textarea>
                            </div>

                            <hr>
                            <div class="form-group d-flex justify-content-between">
                                <a href="{{ url('/admin/movimientos/') }}" class="btn btn-default">Cancelar</a>
                                <button type="submit" class="btn btn-primary">Guardar</button>
                            </div>

                        </form>
                    </div>
                </div>
            </div>
        </div>
    @stop

    @section('css')
    @stop

    {{-- @section('js')
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                const tipo = document.getElementById('tipo');
                const loteSelect = document.getElementById('lote_id');
                const datosNuevoLote = document.getElementById('datos_lote_nuevo');
                // 🚨 NUEVA LÍNEA: Obtener referencia al campo de proveedor
                const proveedorSelect = document.getElementById('proveedor_id');

                function actualizarCampos() {
                    const esEntrada = tipo.value === 'entrada';
                    // Si loteSelect.value es "" (vacío), se creará un lote nuevo.
                    const loteSeleccionado = loteSelect.value !== "";

                    // Si es entrada y NO hay lote seleccionado -> mostrar campos para crear lote
                    if (esEntrada && !loteSeleccionado) {
                        datosNuevoLote.style.display = 'block';
                        // 🚨 APLICAR EL REQUERIDO: Proveedor es obligatorio para crear un lote.
                        proveedorSelect.setAttribute('required', 'required');
                    } else {
                        datosNuevoLote.style.display = 'none';
                        // 🚨 QUITAR EL REQUERIDO: Quitar requisito si se oculta el campo.
                        proveedorSelect.removeAttribute('required');
                    }
                }

                tipo.addEventListener('change', actualizarCampos);
                loteSelect.addEventListener('change', actualizarCampos);
                actualizarCampos();
            });
        </script>
        <script>
            
        </script>
    @stop --}}
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
