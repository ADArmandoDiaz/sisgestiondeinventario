@extends('adminlte::page')

@section('content_header')
    <nav aria-label="breadcrumb" style="font-size: 15pt">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ url('/admin') }}">Inicio</a></li>
            <li class="breadcrumb-item active" aria-current="page">Lotes</li>
        </ol>
    </nav>
    <hr>
@stop

@section('content')
    <div class="row">
        <div class="col-md-12">
            <div class="card card-outline card-primary">
                <div class="card-header">
                    <h3 class="card-title">Lotes registrados</h3>
                    <div class="card-tools">
                        <a class="btn btn-primary" href="{{ url('/admin/lotes/create') }}">Crear nuevo lote</a>
                    </div>
                </div>
                <div class="card-body" style="display: block;">
                    <table id="example1" class="table table-striped table-hover table-bordered table-sm">
                        <thead>
                            <tr>
                                <th>Nro</th>
                                <th>Código Lote</th>
                                <th>Producto</th>
                                <th>Cantidad Actual</th>
                                <th>Fecha Vencimiento</th>
                                <th>Estado</th> {{-- ⬅️ Nueva Columna --}}
                                <th>Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($lotes as $lote)
                                @php
                                    // Lógica para determinar el estado:
                                    // 1. Convertir la fecha de vencimiento a un objeto Carbon
                                    $fechaVencimiento = \Carbon\Carbon::parse(
                                        $lote->fecha_vencimiento ?? now()->addYears(1),
                                    ); // Usar un valor por defecto si es nulo
                                    $vencido = $fechaVencimiento->isPast();
                                @endphp
                                <tr>
                                    <td style="text-align: center">{{ $loop->iteration }}</td>
                                    <td>{{ $lote->codigo_lote }}</td>
                                    <td>{{ $lote->producto->nombre }}</td>
                                    <td>{{ $lote->cantidad_actual }}</td>
                                    <td>{{ $fechaVencimiento->format('d/m/Y') }}</td>

                                    {{-- 🔴🟢 Columna ESTADO con color condicional --}}
                                    <td style="text-align: center">
                                        @if ($vencido)
                                            <span class="badge badge-danger">VENCIDO</span>
                                        @else
                                            <span class="badge badge-success">VIGENTE</span>
                                        @endif
                                    </td>

                                    <td style="text-align: center;">
                                        <div class="btn-group" role="group" aria-label="Acciones" style="gap:5pt">
                                            <a href="{{ url('/admin/lotes/' . $lote->id) }}" class="btn btn-info">
                                                <i class="fas fa-eye"></i> Ver
                                            </a>
                                            <a href="{{ url('/admin/lotes/' . $lote->id . '/edit') }}"
                                                class="btn btn-success">
                                                <i class="fas fa-pencil-alt"></i> Editar
                                            </a>
                                            <form action="{{ url('/admin/lotes/' . $lote->id) }}"
                                                id="formulario{{ $lote->id }}" method="POST" style="display: inline;">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-danger"
                                                    onclick="confirmarEliminar{{ $lote->id }}(event)">
                                                    <i class="fas fa-trash-alt"></i> Eliminar
                                                </button>
                                            </form>
                                            <script>
                                                function confirmarEliminar{{ $lote->id }}(event) {
                                                    event.preventDefault();
                                                    Swal.fire({
                                                        title: "¿Desea eliminar este lote?",
                                                        text: "",
                                                        icon: "question",
                                                        showCancelButton: true,
                                                        confirmButtonColor: "#3085d6",
                                                        cancelButtonColor: "#d33",
                                                        confirmButtonText: "Sí, eliminarlo!"
                                                    }).then((result) => {
                                                        if (result.isConfirmed) {
                                                            document.getElementById('formulario{{ $lote->id }}').submit();
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
            </div>
        </div>
    </div>
@stop

@section('css')
    <style>
        /* Estilos para los botones y DataTables, igual que en categorías */
        #example1_wrapper .dt-buttons {
            background-color: transparent;
            box-shadow: none;
            display: flex;
            justify-content: center;
            gap: 10px;
            margin-bottom: 15px;
        }

        #example1_wrapper .btn {
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
            $("#example1").DataTable({
                "pageLength": 10,
                "language": {
                    "emptyTable": "No hay información",
                    "info": "Mostrando _START_ a _END_ de _TOTAL_ Lotes",
                    "infoEmpty": "Mostrando 0 a 0 de 0 Lotes",
                    "infoFiltered": "(Filtrado de _MAX_ total Lotes)",
                    "lengthMenu": "Mostrar _MENU_ Lotes",
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
