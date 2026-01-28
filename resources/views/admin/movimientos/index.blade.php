@extends('adminlte::page')

@section('content_header')
    <nav aria-label="breadcrumb" style="font-size: 18pt">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ url('/admin') }}">Inicio</a></li>
            <li class="breadcrumb-item"><a href="{{ url('/admin/movimientos') }}">Movimientos</a></li>
            <li class="breadcrumb-item active" aria-current="page">Listado de movimientos</li>
        </ol>
    </nav>
    <hr>
@stop

@section('content')
    <div class="row">
        <div class="col-md-12">
            <div class="card card-outline card-primary">
                <div class="card-header">
                    <h3 class="card-title"><b>Movimientos registrados</b></h3>
                    <div class="card-tools">
                        <a class="btn btn-primary" href="{{ url('/admin/movimientos/create') }}">Registrar movimiento</a>
                    </div>
                </div>
                <div class="card-body" style="display: block;">
                    @if (session('mensaje'))
                        <div class="alert alert-{{ session('icono') }}">
                            {{ session('mensaje') }}
                        </div>
                    @endif

                    <table id="movimientosTable" class="table table-striped table-bordered table-hover table-sm">
                        <thead>
                            <tr>
                                <th>Nro</th>
                                <th>Producto</th>
                                <th>Lote</th>
                                <th>Tipo</th>
                                <th>Cantidad</th>
                                <th>Usuario</th>
                                <th>Fecha</th>
                                <th>Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($movimientos as $movimiento)
                                <tr>
                                    <td style="text-align: center">{{ $loop->iteration }}</td>
                                    <td>{{ $movimiento->producto->nombre }}</td>
                                    <td>{{ $movimiento->lote->codigo_lote ?? 'N/A' }}</td>

                                    {{-- Columna TIPO con colores de badge --}}
                                    <td>
                                        @if ($movimiento->tipo === 'entrada')
                                            <span class="badge badge-success">ENTRADA</span>
                                        @else
                                            <span class="badge badge-danger">SALIDA</span>
                                        @endif
                                    </td>

                                    {{-- Columna CANTIDAD con signo y color --}}
                                    <td>
                                        @if ($movimiento->tipo === 'entrada')
                                            <span style="color: green; font-weight: bold;">+
                                                {{ $movimiento->cantidad }}</span>
                                        @else
                                            <span style="color: red; font-weight: bold;">-
                                                {{ $movimiento->cantidad }}</span>
                                        @endif
                                    </td>

                                    <td>{{ $movimiento->usuario->name ?? 'N/A' }}</td>
                                    <td>{{ $movimiento->created_at->format('d/m/Y H:i') }}</td>
                                    <td style="text-align: center">
                                        <div class="btn-group" role="group">
                                            <a href="{{ url('/admin/movimientos/' . $movimiento->id) }}"
                                                class="btn btn-info btn-sm"><i class="fas fa-eye"></i> Ver</a>
                                            <a href="{{ url('/admin/movimientos/' . $movimiento->id) . '/edit' }}"
                                                class="btn btn-success btn-sm"><i class="fas fa-pencil-alt"></i> Editar</a>
                                            <form action="{{ url('/admin/movimientos/' . $movimiento->id) }}"
                                                method="POST" class="d-inline" id="formEliminar{{ $movimiento->id }}">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-danger btn-sm"
                                                    onclick="confirmarEliminacion{{ $movimiento->id }}(event)">
                                                    <i class="fas fa-trash-alt"></i> Eliminar
                                                </button>
                                            </form>
                                            <script>
                                                function confirmarEliminacion{{ $movimiento->id }}(event) {
                                                    event.preventDefault();
                                                    Swal.fire({
                                                        title: '¿Desea eliminar este movimiento?',
                                                        icon: 'question',
                                                        showCancelButton: true,
                                                        confirmButtonColor: '#3085d6',
                                                        cancelButtonColor: '#d33',
                                                        confirmButtonText: 'Sí, eliminar'
                                                    }).then((result) => {
                                                        if (result.isConfirmed) {
                                                            document.getElementById('formEliminar{{ $movimiento->id }}').submit();
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

                    {{ $movimientos->links() }}
                </div>
            </div>
        </div>
    </div>
@stop

@section('css')
    <style>
        #movimientosTable_wrapper .dt-buttons {
            background-color: transparent;
            box-shadow: none;
            border: none;
            display: flex;
            justify-content: center;
            gap: 10px;
            margin-bottom: 15px;
        }

        #movimientosTable_wrapper .btn {
            color: #fff;
            border-radius: 4px;
            padding: 5px 15px;
            font-size: 14px;
        }

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
    <script>
        $(function() {
            $("#movimientosTable").DataTable({
                "pageLength": 10,
                "language": {
                    "emptyTable": "No hay información",
                    "info": "Mostrando _START_ a _END_ de _TOTAL_ movimientos",
                    "infoEmpty": "Mostrando 0 a 0 de 0 movimientos",
                    "infoFiltered": "(Filtrado de _MAX_ total movimientos)",
                    "lengthMenu": "Mostrar _MENU_ movimientos",
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
            }).buttons().container().appendTo('#movimientosTable_wrapper .row:eq(0)');
        });
    </script>
@stop
