@extends('adminlte::page')

@section('title', 'Historial de Acciones')

@section('content_header')
    <h1><i class="fas fa-history"></i> Historial de Acciones de Usuarios</h1>
@stop

@section('content')

    <div class="card">
        <div class="card-body">
            {{-- La tabla ahora tiene el ID 'example1' para DataTables --}}
            <table id="example1" class="table table-bordered table-striped table-hover table-sm">
                <thead>
                    <tr>
                        <th style="width: 15%">Usuario</th>
                        <th style="width: 20%">Acción</th>
                        <th style="width: 50%">Descripción</th>
                        <th style="width: 15%">Fecha</th>
                    </tr>
                </thead>
                <tbody>
                    {{-- Usando $logs, asumimos que es una Collection o Array para DataTables --}}
                    @forelse ($logs as $log)
                        <tr>
                            {{-- 1. USUARIO: Lee de la relación 'user' (asociada a user_id) o por defecto 'Sistema' --}}
                            <td>{{ $log->user ? $log->user->name : 'Sistema' }}</td>

                            {{-- 2. ACCIÓN: Lee de la columna 'accion' --}}
                            <td>{{ $log->accion }}</td>

                            {{-- 3. DESCRIPCIÓN: Lee de la columna 'descripcion' --}}
                            <td>{!! $log->descripcion !!}</td>

                            {{-- 4. FECHA: La columna 'created_at' --}}
                            <td>{{ $log->created_at->format('d/m/Y H:i') }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="text-center">No hay registros en el historial.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>

            <div class="mt-3">
                {{-- Si estás usando DataTables, generalmente no se usa $logs->links() --}}
                {{-- Puedes dejarlo si también quieres paginación del lado del servidor --}}
            </div>
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
            /* TamaÃ±o de fuente */
        }

        /* Colores por tipo de botÃ³n */
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
            $("#example1").DataTable({
                "pageLength": 10,
                "language": {
                    "emptyTable": "No hay información",
                    "info": "Mostrando _START_ a _END_ de _TOTAL_ registros",
                    "infoEmpty": "Mostrando 0 a 0 de 0 registros",
                    "infoFiltered": "(Filtrado de _MAX_ total de registros)",
                    "lengthMenu": "Mostrar _MENU_ registros",
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
@stop
