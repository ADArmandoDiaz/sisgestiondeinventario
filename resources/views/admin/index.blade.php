@extends('adminlte::page')

@section('title', 'Dashboard')

@section('content_header')
    <h1>Dashboard</h1>
@stop

@section('content')
    <p>Bienvenido </p>
    <hr>
    <div class="row">

        {{-- <div class="col-md-3 col-sm-6 col-12">
            <div class="info-box">
                <a href="{{ url('admin/sucursales') }}">
                    <span class="info-box-icon bg-info">
                        <img src="{{ url('img/gif/edificio.gif') }}" alt="">
                    </span>
                </a>

                <div class="info-box-content">
                    <span class="info-box-text"><b>Sucursales</b></span>
                    <span class="info-box-number">{{ $total_sucursales }} Sucursales</span>
                </div>
                <!-- /.info-box-content -->
            </div>
            <!-- /.info-box -->
        </div> --}}
        <div class="col-md-3 col-sm-6 col-12">
            <div class="info-box">
                <a href="{{ url('admin/categorias') }}">
                    <span class="info-box-icon bg-info">
                        <img src="{{ url('img/gif/libros.gif') }}" alt="">
                    </span>
                </a>

                <div class="info-box-content">
                    <span class="info-box-text"><b>Categorías</b></span>
                    <span class="info-box-number">{{ $total_categorias }} Categorías</span>
                    <span class="info-box-text" style="color: white"> ...</span>
                </div>
                <!-- /.info-box-content -->
            </div>
            <!-- /.info-box -->
        </div>
        <div class="col-md-3 col-sm-6 col-12">
            <div class="info-box">
                <a href="{{ url('admin/productos') }}">
                    <span class="info-box-icon bg-info">
                        <img src="{{ url('img/gif/paquete.gif') }}" alt="">
                    </span>
                </a>

                <div class="info-box-content">
                    <span class="info-box-text"><b>Productos</b></span>

                    <!-- Total de productos -->
                    <span class="info-box-number">{{ $total_productos }} Productos</span>

                    <!-- Problemas de stock -->
                    <span class="info-box-number text-danger" style="font-size: 13px;">
                        {{ $problemas_stock }} con problemas de stock
                    </span>
                </div>
            </div>
        </div>

        <div class="col-md-3 col-sm-6 col-12">
            <div class="info-box">
                <a href="{{ url('admin/proveedores') }}">
                    <span class="info-box-icon bg-info">
                        <img src="{{ url('img/gif/camion.gif') }}" alt="">
                    </span>
                </a>

                <div class="info-box-content">
                    <span class="info-box-text"><b>Proveedores</b></span>
                    <span class="info-box-number">{{ $total_proveedores }} Proveedores</span>
                    <span class="info-box-text" style="color: white"> ...</span>

                </div>
                <!-- /.info-box-content -->
            </div>
            <!-- /.info-box -->
        </div>

        <div class="col-md-3 col-sm-6 col-12">
            <div class="info-box">
                <a href="{{ url('admin/movimientos') }}">
                    <span class="info-box-icon bg-info">
                        <img src="{{ url('img/gif/toma-de-decisiones.gif') }}" alt="">
                    </span>
                </a>

                <div class="info-box-content">
                    <span class="info-box-text"><b>Movimientos</b></span>
                    <span class="info-box-number">{{ $total_movimientos }} Movimientos</span>
                    <span class="info-box-text" style="color: white"> ...</span>

                </div>
                <!-- /.info-box-content -->
            </div>
            <!-- /.info-box -->
        </div>
        <div class="col-md-3 col-sm-6 col-12">
            <div class="info-box">
                <a href="{{ url('admin/lotes') }}">
                    <span class="info-box-icon bg-info">
                        <img src="{{ url('img/gif/inventario.gif') }}" alt="">
                    </span>
                </a>

                <div class="info-box-content">
                    <span class="info-box-text"><b>Lotes</b></span>

                    <!-- Total de lotes -->
                    <span class="info-box-number">{{ $total_lotes }} Lotes</span>

                    <!-- Lotes vencidos -->
                    <span class="info-box-number text-danger" style="font-size: 13px;">
                        {{ $total_lotes_vencidos }} Lotes vencidos
                    </span>
                </div>
            </div>
        </div>

        {{-- <div class="col-md-3 col-sm-6 col-12">
            <div class="info-box">
                <a href="{{ url('admin/compras') }}">
                    <span class="info-box-icon bg-info">
                        <img src="{{ url('img/gif/carro-de-la-compra.gif') }}" alt="">
                    </span>
                </a>

                <div class="info-box-content">
                    <span class="info-box-text"><b>Compras</b></span>
                    <span class="info-box-number">{{ $total_compras }} Compras</span>
                </div>
                <!-- /.info-box-content -->
            </div>
            <!-- /.info-box -->
        </div> --}}
    </div>

@stop

@section('css')
    {{-- Add here extra stylesheets --}}
    {{-- <link rel="stylesheet" href="/css/admin_custom.css"> --}}
    <link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.3/css/select2.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css" rel="stylesheet">
@stop

@section('js')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.3/js/select2.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.all.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js@3.8.0/dist/chart.min.js"></script>


@stop
