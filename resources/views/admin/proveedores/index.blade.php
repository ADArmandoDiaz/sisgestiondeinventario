@extends('adminlte::page')

@section('content_header')
    <nav aria-label="breadcrumb" style="font-size: 18pt">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ url('/admin') }}">Inicio</a></li>
            <li class="breadcrumb-item"><a href="{{ url('/admin/proveedores') }}">Proveedores</a></li>
            <li class="breadcrumb-item active" aria-current="page">Listado de proveedores</li>
        </ol>
    </nav>
    <hr>
@stop

@section('content')
    <div class="row">
        <div class="col-md-12">
            <div class="card card-outline card-primary ">
                <div class="card-header">
                    <h3 class="card-title"><b>Proveedores registrados</b></h3>

                    <div class="card-tools">
                        <!-- Button trigger modal -->
                        <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#modalCreate">
                            Crear nuevo
                        </button>

                        <!-- Modal -->
                        <div class="modal fade" id="modalCreate" tabindex="-1" aria-labelledby="modalCreateLabel"
                            aria-hidden="true">
                            <div class="modal-dialog">
                                <div class="modal-content">
                                    <div class="modal-header" style="background-color: #007bff; color: white;">
                                        <h5 class="modal-title fs-5" id="modalCreateLabel">Crear nuevo proveedor</h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal"
                                            aria-label="Close"></button>
                                    </div>
                                    <div class="modal-body">
                                        <form action="{{ url('/admin/proveedores/create') }}" method="POST">
                                            {{-- class="form-horizontal"> --}}
                                            @csrf
                                            <div class="row">
                                                <div class="col-md-12">
                                                    <div class="form-group">
                                                        <label for="nombre_empresa">Empresa <b
                                                                style="color: red">(*)</b></label>
                                                        <div class="input-group mb-4">
                                                            <div class="input-group-prepend">
                                                                <span class="input-group-text"><i
                                                                        class="fas fa-building"></i></span>
                                                            </div>
                                                            <input type="text" value="{{ old('nombre_empresa') }}"
                                                                class="form-control" id="nombre_empresa"
                                                                name="nombre_empresa"
                                                                placeholder="Ingrese el nombre de la empresa..." required>
                                                        </div>
                                                        @error('nombre_empresa')
                                                            <small style="color: red">{{ $message }}</small>
                                                        @enderror
                                                    </div>
                                                </div>
                                                <div class="col-md-12">
                                                    <div class="form-group">
                                                        <label for="contacto_nombre">Nombre del contacto <b
                                                                style="color: red">(*)</b></label>
                                                        <div class="input-group mb-4">
                                                            <div class="input-group-prepend">
                                                                <span class="input-group-text"><i
                                                                        class="fas fa-user"></i></span>
                                                            </div>
                                                            <input type="text" value="{{ old('contacto_nombre') }}"
                                                                class="form-control" id="contacto_nombre"
                                                                name="contacto_nombre"
                                                                placeholder="Ingrese el nombre del contacto..." required>
                                                        </div>
                                                        @error('contacto_nombre')
                                                            <small style="color: red">{{ $message }}</small>
                                                        @enderror
                                                    </div>
                                                </div>
                                                <div class="col-md-12">
                                                    <div class="form-group">
                                                        <label for="contacto_telefono">Teléfono del contacto <b
                                                                style="color: red">(*)</b></label>
                                                        <div class="input-group mb-4">
                                                            <div class="input-group-prepend">
                                                                <span class="input-group-text"><i
                                                                        class="fas fa-phone"></i></span>
                                                            </div>
                                                            <input type="text" value="{{ old('contacto_telefono') }}"
                                                                class="form-control" id="contacto_telefono"
                                                                name="contacto_telefono"
                                                                placeholder="Ingrese el teléfono del contacto..." required>
                                                        </div>
                                                        @error('contacto_telefono')
                                                            <small style="color: red">{{ $message }}</small>
                                                        @enderror
                                                    </div>
                                                </div>
                                                <div class="col-md-12">
                                                    <div class="form-group">
                                                        <label for="contacto_email">Email del contacto <b
                                                                style="color: red">(*)</b></label>
                                                        <div class="input-group mb-4">
                                                            <div class="input-group-prepend">
                                                                <span class="input-group-text"><i
                                                                        class="fas fa-envelope"></i></span>
                                                            </div>
                                                            <input type="text" value="{{ old('contacto_email') }}"
                                                                class="form-control" id="contacto_email"
                                                                name="contacto_email"
                                                                placeholder="Ingrese el email del contacto..." required>
                                                        </div>
                                                        @error('contacto_email')
                                                            <small style="color: red">{{ $message }}</small>
                                                        @enderror
                                                    </div>
                                                </div>
                                                <div class="col-md-12">
                                                    <div class="form-group">
                                                        <label for="direccion">Dirección del contacto <b
                                                                style="color: red">(*)</b></label>
                                                        <div class="input-group mb-4">
                                                            <div class="input-group-prepend">
                                                                <span class="input-group-text"><i
                                                                        class="fas fa-map-marker-alt"></i></span>
                                                            </div>
                                                            <input type="text" value="{{ old('direccion') }}"
                                                                class="form-control" id="direccion" name="direccion"
                                                                placeholder="Ingrese la dirección del contacto..."
                                                                required>
                                                        </div>
                                                        @error('direccion')
                                                            <small style="color: red">{{ $message }}</small>
                                                        @enderror
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-md-12 text-center">
                                                    <button type="button" class="btn btn-secondary"
                                                        data-bs-dismiss="modal">Cancelar</button>
                                                    <button type="submit" class="btn btn-primary">Guardar</button>
                                                </div>
                                            </div>
                                        </form>
                                    </div>

                                </div>
                            </div>
                        </div>
                        <!-- /.card-tools -->
                    </div>
                </div>
                <!-- /.card-header -->
                <div class="card-body " style="display: block;">
                    <table id="example1"
                        class="table table-striped table-bordered table-hover table-sm table-responsive">
                        <thead>
                            <tr>
                                <th>Nro</th>
                                <th>Empresa</th>
                                <th>Nombre</th>
                                <th>Teléfono</th>
                                <th>Email</th>
                                <th>Dirección</th>
                                <th>Acciones</th>

                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($proveedores as $proveedor)
                                <tr>
                                    <td style="text-align: center">{{ $loop->iteration }}</td>
                                    <td>{{ $proveedor->nombre_empresa }}</td>
                                    <td>{{ $proveedor->contacto_nombre }}</td>
                                    <td>{{ $proveedor->contacto_telefono }}</td>
                                    <td>{{ $proveedor->contacto_email }}</td>
                                    <td>{{ $proveedor->direccion }}</td>
                                    <td>
                                        <div class="btn-group" role="group" aria-label="Basic example"
                                            style="gap: 7px">
                                            <!-- Button trigger modal -->
                                            <button type="button" class="btn btn-info" data-bs-toggle="modal"
                                                data-bs-target="#modalShow{{ $proveedor->id }}">
                                                <i class="fas fa-eye"></i> Ver
                                            </button>
                                            <!-- Modal -->
                                            <div class="modal fade" id="modalShow{{ $proveedor->id }}" tabindex="-1"
                                                aria-labelledby="modalShowLabel{{ $proveedor->id }}" aria-hidden="true">
                                                <div class="modal-dialog">
                                                    <div class="modal-content">
                                                        <div class="modal-header"
                                                            style="background-color: #17a2b8; color: white;">
                                                            <h5 class="modal-title fs-5"
                                                                id="modalShowLabel{{ $proveedor->id }}">Datos del
                                                                proveedor</h5>
                                                            <button type="button" class="btn-close"
                                                                data-bs-dismiss="modal" aria-label="Close"></button>
                                                        </div>
                                                        <div class="modal-body">
                                                            <div class="row">
                                                                <div class="col-md-12">
                                                                    <div class="form-group">
                                                                        <label for="nombre_empresa">Empresa </label>
                                                                        <p>{{ $proveedor->nombre_empresa }}</p>
                                                                    </div>
                                                                </div>
                                                                <div class="col-md-12">
                                                                    <div class="form-group">
                                                                        <label for="contacto_nombre">Nombre del
                                                                            contacto </label>
                                                                        <p>{{ $proveedor->contacto_nombre }}</p>

                                                                    </div>
                                                                </div>
                                                                <div class="col-md-12">
                                                                    <div class="form-group">
                                                                        <label for="contacto_telefono">Teléfono del
                                                                            contacto </label>
                                                                        <p>{{ $proveedor->contacto_telefono }}</p>

                                                                    </div>
                                                                </div>
                                                                <div class="col-md-12">
                                                                    <div class="form-group">
                                                                        <label for="contacto_email">Email del
                                                                            contacto</label>
                                                                        <p>{{ $proveedor->contacto_email }}</p>

                                                                    </div>
                                                                </div>
                                                                <div class="col-md-12">
                                                                    <div class="form-group">
                                                                        <label for="direccion">Dirección del contacto
                                                                        </label>
                                                                        <p>{{ $proveedor->direccion }}</p>
                                                                    </div>
                                                                </div>

                                                            </div>
                                                            <hr>

                                                            <div class="row">
                                                                <div class="col-md-12">
                                                                    <button type="button" class="btn btn-secondary"
                                                                        data-bs-dismiss="modal">Cerrar</button>

                                                                </div>
                                                            </div>

                                                        </div>
                                                    </div>
                                                </div>
                                            </div>


                                            <!-- Button trigger modal -->
                                            <button type="button" class="btn btn-success" data-bs-toggle="modal"
                                                data-bs-target="#modalEdit{{ $proveedor->id }}">
                                                <i class="fas fa-pencil-alt"></i> Editar
                                            </button>

                                            <!-- Modal -->
                                            <div class="modal fade" id="modalEdit{{ $proveedor->id }}" tabindex="-1"
                                                aria-labelledby="modalEditLabel" aria-hidden="true">
                                                <div class="modal-dialog">
                                                    <div class="modal-content">
                                                        <div class="modal-header"
                                                            style="background-color: #28a745; color: white;">
                                                            <h5 class="modal-title fs-5" id="modalEditLabel">Editar
                                                                proveedor</h5>
                                                            <button type="button" class="btn-close"
                                                                data-bs-dismiss="modal" aria-label="Close"></button>
                                                        </div>
                                                        <div class="modal-body">
                                                            <form
                                                                action="{{ url('/admin/proveedores/' . $proveedor->id) }}"
                                                                method="POST">
                                                                @method('PUT')
                                                                {{-- class="form-horizontal"> --}}
                                                                @csrf
                                                                <div class="row">
                                                                    <div class="col-md-12">
                                                                        <div class="form-group">
                                                                            <label for="nombre_empresa">Empresa <b
                                                                                    style="color: red">(*)</b></label>
                                                                            <div class="input-group mb-4">
                                                                                <div class="input-group-prepend">
                                                                                    <span class="input-group-text"><i
                                                                                            class="fas fa-building"></i></span>
                                                                                </div>
                                                                                <input type="text"
                                                                                    value="{{ old('nombre_empresa', $proveedor->nombre_empresa) }}"
                                                                                    class="form-control"
                                                                                    id="nombre_empresa"
                                                                                    name="nombre_empresa"
                                                                                    placeholder="Ingrese el nombre de la empresa..."
                                                                                    required>
                                                                            </div>
                                                                            @error('nombre_empresa')
                                                                                <small
                                                                                    style="color: red">{{ $message }}</small>
                                                                            @enderror
                                                                        </div>
                                                                    </div>
                                                                    <div class="col-md-12">
                                                                        <div class="form-group">
                                                                            <label for="contacto_nombre">Nombre del
                                                                                contacto <b
                                                                                    style="color: red">(*)</b></label>
                                                                            <div class="input-group mb-4">
                                                                                <div class="input-group-prepend">
                                                                                    <span class="input-group-text"><i
                                                                                            class="fas fa-user"></i></span>
                                                                                </div>
                                                                                <input type="text"
                                                                                    value="{{ old('contacto_nombre', $proveedor->contacto_nombre) }}"
                                                                                    class="form-control"
                                                                                    id="contacto_nombre"
                                                                                    name="contacto_nombre"
                                                                                    placeholder="Ingrese el nombre del contacto..."
                                                                                    required>
                                                                            </div>
                                                                            @error('contacto_nombre')
                                                                                <small
                                                                                    style="color: red">{{ $message }}</small>
                                                                            @enderror
                                                                        </div>
                                                                    </div>
                                                                    <div class="col-md-12">
                                                                        <div class="form-group">
                                                                            <label for="contacto_telefono">Teléfono del
                                                                                contacto <b
                                                                                    style="color: red">(*)</b></label>
                                                                            <div class="input-group mb-4">
                                                                                <div class="input-group-prepend">
                                                                                    <span class="input-group-text"><i
                                                                                            class="fas fa-phone"></i></span>
                                                                                </div>
                                                                                <input type="text"
                                                                                    value="{{ old('contacto_telefono', $proveedor->contacto_telefono) }}"
                                                                                    class="form-control"
                                                                                    id="contacto_telefono"
                                                                                    name="contacto_telefono"
                                                                                    placeholder="Ingrese el teléfono del contacto..."
                                                                                    required>
                                                                            </div>
                                                                            @error('contacto_telefono')
                                                                                <small
                                                                                    style="color: red">{{ $message }}</small>
                                                                            @enderror
                                                                        </div>
                                                                    </div>
                                                                    <div class="col-md-12">
                                                                        <div class="form-group">
                                                                            <label for="contacto_email">Email del contacto
                                                                                <b style="color: red">(*)</b></label>
                                                                            <div class="input-group mb-4">
                                                                                <div class="input-group-prepend">
                                                                                    <span class="input-group-text"><i
                                                                                            class="fas fa-envelope"></i></span>
                                                                                </div>
                                                                                <input type="text"
                                                                                    value="{{ old('contacto_email', $proveedor->contacto_email) }}"
                                                                                    class="form-control"
                                                                                    id="contacto_email"
                                                                                    name="contacto_email"
                                                                                    placeholder="Ingrese el email del contacto..."
                                                                                    required>
                                                                            </div>
                                                                            @error('contacto_email')
                                                                                <small
                                                                                    style="color: red">{{ $message }}</small>
                                                                            @enderror
                                                                        </div>
                                                                    </div>
                                                                    <div class="col-md-12">
                                                                        <div class="form-group">
                                                                            <label for="direccion">Dirección del contacto
                                                                                <b style="color: red">(*)</b></label>
                                                                            <div class="input-group mb-4">
                                                                                <div class="input-group-prepend">
                                                                                    <span class="input-group-text"><i
                                                                                            class="fas fa-map-marker-alt"></i></span>
                                                                                </div>
                                                                                <input type="text"
                                                                                    value="{{ old('direccion', $proveedor->direccion) }}"
                                                                                    class="form-control" id="direccion"
                                                                                    name="direccion"
                                                                                    placeholder="Ingrese la dirección del contacto..."
                                                                                    required>
                                                                            </div>
                                                                            @error('direccion')
                                                                                <small
                                                                                    style="color: red">{{ $message }}</small>
                                                                            @enderror
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                                <hr>
                                                                <div class="row">
                                                                    <div class="col-md-12">
                                                                        <button type="button" class="btn btn-secondary"
                                                                            data-bs-dismiss="modal">Cancelar</button>
                                                                        <button type="submit"
                                                                            class="btn btn-success">Guardar</button>
                                                                    </div>
                                                                </div>
                                                            </form>
                                                        </div>

                                                    </div>
                                                </div>
                                            </div>


                                            <form action="{{ url('/admin/proveedores/' . $proveedor->id) }}"
                                                id="formulario{{ $proveedor->id }}" method="POST" class="d-inline">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-danger"
                                                    onclick="preguntar{{ $proveedor->id }}(event)">
                                                    <i class="fas fa-trash-alt"></i> Eliminar</button>
                                            </form>
                                            <script>
                                                function preguntar{{ $proveedor->id }}(event) {
                                                    event.preventDefault(); // Evita el envío del formulario
                                                    Swal.fire({
                                                        title: "¿Desea eliminar este registro?",
                                                        text: "",
                                                        icon: "question",
                                                        showCancelButton: true,
                                                        confirmButtonColor: "#3085d6",
                                                        cancelButtonColor: "#d33",
                                                        confirmButtonText: "Si, eliminar",
                                                        denyButtonText: "No, cancelar"
                                                    }).then((result) => {
                                                        if (result.isConfirmed) {
                                                            document.getElementById('formulario{{ $proveedor->id }}').submit();
                                                        }
                                                    });
                                                }
                                            </script>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                <!-- /.card-body -->
                <!-- /.card -->
            </div>
        </div>
    @stop

    @section('css')
        <style>
            /* Fondo transparente y sin borde en el contenedor */
            #example1_wrapper .dt-buttons {
                background-color: transparent;
                box-shadow: none;
                border: none;
                display: flex;
                justify-content: center;
                /* Centrar los botones */
                gap: 10px;
                /* Espaciado entre botones */
                margin-bottom: 15px;
                /* Separar botones de la tabla */
            }

            /* Estilo personalizado para los botones */
            #example1_wrapper .btn {
                color: #fff;
                /* Color del texto en blanco */
                border-radius: 4px;
                /* Bordes redondeados */
                padding: 5px 15px;
                /* Espaciado interno */
                font-size: 14px;
                /* Tamaño de fuente */
            }

            /* Colores por tipo de botón */
            .btn-danger {
                background-color: #dc3545;
                border: none;
            }

            .btn-success {
                background-color: #28a745;
                border: none;
            }

            .btn-info {
                background-color: #17a2b8;
                border: none;
            }

            .btn-warning {
                background-color: #ffc107;
                color: #212529;
                border: none;
            }

            .btn-default {
                background-color: #6e7176;
                color: #212529;
                border: none;
            }
        </style>
    @stop

    @section('js')
        @if ($errors->any())
            <script>
                @if (session('modal_id'))
                    var modalId = "{{ session('modal_id') }}";
                    $('#modalEdit' + modalId).modal('show');
                @else
                    $('#modalCreate').modal('show');
                @endif
            </script>
        @endif
        <script>
            $(function() {
                $("#example1").DataTable({
                    // "scrollx": false,
                    // "autoWidth": false,
                    // "responsive": false,
                    "pageLength": 10,
                    "language": {
                        "emptyTable": "No hay información",
                        "info": "Mostrando _START_ a _END_ de _TOTAL_ Proveedores",
                        "infoEmpty": "Mostrando 0 a 0 de 0 Proveedores",
                        "infoFiltered": "(Filtrado de _MAX_ total Proveedores)",
                        "lengthMenu": "Mostrar _MENU_ Proveedores",
                        "loadingRecords": "Cargando...",
                        "processing": "Procesando...",
                        "search": "Buscador:",
                        "zeroRecords": "Sin resultados encontrados",
                        "paginate": {
                            "first": "Primero",
                            "last": "Último",
                            "next": "Siguiente",
                            "previous": "Anterior"
                        }
                    },
                    "responsive": true,
                    "lengthChange": true,
                    "autoWidth": false,
                    buttons: [{
                            text: '<i class="fas fa-copy"></i> COPIAR',
                            extend: 'copy',
                            className: 'btn btn-default'
                        },
                        {
                            text: '<i class="fas fa-file-pdf"></i> PDF',
                            extend: 'pdf',
                            className: 'btn btn-danger'
                        },
                        {
                            text: '<i class="fas fa-file-csv"></i> CSV',
                            extend: 'csv',
                            className: 'btn btn-info'
                        },
                        {
                            text: '<i class="fas fa-file-excel"></i> EXCEL',
                            extend: 'excel',
                            className: 'btn btn-success'
                        },
                        {
                            text: '<i class="fas fa-print"></i> IMPRIMIR',
                            extend: 'print',
                            className: 'btn btn-warning'
                        }
                    ]
                }).buttons().container().appendTo('#example1_wrapper .row:eq(0)');
            });
        </script>

        <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js"
            integrity="sha384-I7E8VVD/ismYTF4hNIPjVp/Zjvgyol6VFvRkX/vR+Vc4jQkC+hVqc2pM8ODewa9r" crossorigin="anonymous">
        </script>
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.min.js"
            integrity="sha384-G/EV+4j2dNv+tEPo3++6LCgdCROaejBqfUeNjuKAiuXbjrxilcCdDz6ZAVfHWe1Y" crossorigin="anonymous">
        </script>
    @stop
