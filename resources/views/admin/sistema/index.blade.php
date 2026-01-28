@extends('adminlte::page')

@section('title', 'Sistema')

@section('content_header')
    <h1><i class="fas fa-cogs"></i> Sistema</h1>
@stop

@section('content')

    {{-- Mensajes --}}
    @if (session('mensaje'))
        <div class="alert alert-{{ session('icono') }}">
            {{ session('mensaje') }}
        </div>
    @endif

    <div class="row">

        {{-- Panel de respaldo --}}
        <div class="col-md-6">
            <div class="card card-primary card-outline">
                <div class="card-header">
                    <h3 class="card-title"><i class="fas fa-database"></i> Respaldo de la Base de Datos</h3>
                </div>
                <div class="card-body">
                    <p>Puedes generar un respaldo completo de la base de datos.</p>

                    <form action="{{ route('sistema.respaldar') }}" method="POST">
                        @csrf
                        <button class="btn btn-primary">
                            <i class="fas fa-download"></i> Descargar Respaldo
                        </button>
                    </form>
                </div>
            </div>
        </div>

        {{-- Panel de información del sistema --}}
        <div class="col-md-6">
            <div class="card card-info card-outline">
                <div class="card-header">
                    <h3 class="card-title"><i class="fas fa-info-circle"></i> Información del Sistema</h3>
                </div>
                <div class="card-body">
                    <ul class="list-group">
                        <li class="list-group-item">
                            <strong>Motor de base de datos:</strong> {{ $db_driver }}
                        </li>
                        <li class="list-group-item">
                            <strong>Versión Laravel:</strong> {{ $laravel_version }}
                        </li>
                        <li class="list-group-item">
                            <strong>PHP versión:</strong> {{ $php_version }}
                        </li>
                        <li class="list-group-item">
                            <strong>Total de tablas:</strong> {{ $total_tablas }}
                        </li>
                        <li class="list-group-item">
                            <strong>Total de registros:</strong> {{ $total_registros }}
                        </li>
                    </ul>
                </div>
            </div>
        </div>

    </div>

@stop
