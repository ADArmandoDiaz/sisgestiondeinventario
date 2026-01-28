@extends('adminlte::page')

{{-- @section('title', 'Sucursales') --}}

@section('content_header')
    <nav aria-label="breadcrumb" style="font-size: 15pt">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ url('/admin') }}">Inicio</a></li>
            <li class="breadcrumb-item"><a href="{{ url('/admin/compras') }}">Compras</a></li>
            <li class="breadcrumb-item active" aria-current="page">Creación de compras</li>
        </ol>
    </nav>
    <hr>
@stop

@section('content')
    <div class="row">
        <div class="col-md-12">
            <div class="card card-primary">
                <div class="card-header">
                    <h3 class="card-title">Paso N° 1 | Llene los datos del formulario</h3>


                    <!-- /.card-tools -->
                </div>
                <!-- /.card-header -->
                <div class="card-body" style="display: block;">
                    <form action="{{ url('/admin/compras/create') }}" method="POST">
                        @csrf

                        <div class="row">
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label for="nombre">Proveedor <b style="color: red">(*)</b></label>
                                    <div class="input-group mb-4">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text"><i class="fas fa-truck"></i></span>
                                        </div>
                                        <select name="proveedor_id" id="proveedor_id" class="form-control" required>
                                            <option value="">Seleccione un proveedor...</option>
                                            @foreach ($proveedores as $proveedor)
                                                <option value="{{ $proveedor->id }}"
                                                    {{ old('proveedor_id') == $proveedor->id ? 'selected' : '' }}>
                                                    {{ $proveedor->nombre . ' | ' . $proveedor->nombre_empresa }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                    @error('proveedor_id')
                                        <small style="color: red">{{ $message }}</small>
                                    @enderror

                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label for="nombre">Fecha <b style="color: red">(*)</b></label>
                                    <div class="input-group mb-4">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text"><i class="fas fa-calendar-alt"></i></span>
                                        </div>
                                        <input type="date" name="fecha_compra" id="fecha_compra" class="form-control"
                                            value="{{ old('fecha_compra') ?? date('Y-m-d') }}" required>
                                    </div>
                                    @error('fecha_compra')
                                        <small style="color: red">{{ $message }}</small>
                                    @enderror

                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="nombre">Observaciones </label>
                                    <div class="input-group mb-4">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text"><i class="fas fa-comment"></i></span>
                                        </div>
                                        <input type="text" name="observaciones" id="observaciones" class="form-control"
                                            value="{{ old('observaciones') }}" required>
                                    </div>
                                    @error('observaciones')
                                        <small style="color: red">{{ $message }}</small>
                                    @enderror

                                </div>
                            </div>
                        </div>
                        <hr>
                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <a href="{{ url('admin/compras') }}" class="btn btn-default">Cancelar</a>
                                    <button type="submit" class="btn btn-primary">Crear orden de compra y agregar
                                        productos</button>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
                <!-- /.card-body -->
            </div>
            <!-- /.card -->
        </div>
    </div>
@stop

@section('css')

    <style>
        .ck.ck-editor {
            width: 100% !important;
        }

        .ck-editor__editable {
            width: 100% !important;
            min-height: 300px;
            box-sizing: border-box;
        }

        .ck.ck-toolbar {
            flex-wrap: wrap;
        }

        @media (max-width: 768px) {
            .ck-editor__editable {
                min-height: 250px;
                padding: 10px;
            }
        }
    </style>

@stop

@section('js')
    <script src="https://cdn.ckeditor.com/ckeditor5/41.4.2/classic/ckeditor.js"></script>
    <script>
        ClassicEditor
            .create(document.querySelector('#description'), {
                toolbar: {
                    items: [
                        'heading', '|',
                        'bold', 'italic', 'underline', 'strikethrough', 'subscript', 'superscript', '|',
                        'link', 'bulletedList', 'numberedList', '|',
                        'outdent', 'indent', '|',
                        'alignment', '|',
                        'blockQuote', 'insertTable', 'mediaEmbed', '|',
                        'undo', 'redo', '|',
                        'fontBackgroundColor', 'fontColor', 'fontSize', 'fontFamily', '|',
                        'code', 'codeBlock', 'htmlEmbed', '|',
                        'sourceEditing'
                    ],
                    shouldNotGroupWhenFull: true
                },
                language: 'es'
            })
            .then(editor => {
                // Forzar responsive después de crear el editor
                const editorEl = editor.ui.view.element;
                editorEl.style.width = '100%';
                // editorEl.querySelector('.ck-editor__editable').style.width = '100%';
            })
            .catch(error => {
                console.error(error);
            });
    </script>
@stop
