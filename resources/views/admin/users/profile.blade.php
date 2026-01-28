@extends('adminlte::page')

@section('title', 'Gestión de Permisos de Usuarios')
{{-- 
@section('content_header')
    <h1 class="m-0 text-dark"><i class="fas fa-user-shield"></i> Permisos de Usuarios</h1>
@stop --}}
@section('content_header')
    <div class="d-flex justify-content-between align-items-center">
        <h1 class="m-0 text-dark">
            <i class="fas fa-user-shield"></i> Permisos de Usuarios
        </h1>

        <!-- BOTÓN PARA HISTORIAL DE ACCIONES -->
        {{-- <a href="{{ route('admin.users.history') }}" class="btn btn-info">
            <i class="fas fa-history"></i> Historial de acciones
        </a> --}}
    </div>
@stop



@section('content')
    <div class="container-fluid">

        <table class="table table-bordered table-striped">
            <thead>
                <tr>
                    <th>Nombre</th>
                    <th>Email</th>
                    {{-- <th>Contraseña</th> <!-- Nueva columna --> --}}
                    <th>Permisos</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($users as $user)
                    <tr>
                        <td>{{ $user->name }}</td>
                        <td>{{ $user->email }}</td>
                        {{-- <td>{{ $user->password_text ?? 'No disponible' }}</td> <!-- Mostrar contraseña --> --}}
                        <td>
                            @foreach ($user->getAllPermissions() as $perm)
                                <span class="badge badge-primary">{{ $perm->name }}</span>
                            @endforeach
                        </td>
                        <td>
                            <button class="btn btn-sm btn-warning" data-toggle="modal"
                                data-target="#editUserModal{{ $user->id }}">
                                <i class="fas fa-edit"></i> Editar
                            </button>

                            <!-- Botón eliminar -->
                            <form action="{{ route('admin.users.destroy', $user->id) }}" method="POST"
                                {{-- USANDO route() --}} style="display:inline-block;"
                                onsubmit="return confirm('¿Seguro que deseas eliminar el usuario {{ $user->name }}?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-danger"
                                    @if (Auth::user()->id === $user->id) disabled title="No puedes eliminar tu propio usuario" @endif>
                                    <i class="fas fa-trash-alt"></i> Eliminar
                                </button>
                            </form>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>

        @foreach ($users as $user)
            <div class="modal fade" id="editUserModal{{ $user->id }}" tabindex="-1" role="dialog">
                <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
                    <div class="modal-content" style="border-radius: 15px; overflow: hidden;">

                        <!-- HEADER -->
                        <div class="modal-header"
                            style="background: linear-gradient(135deg, #4e73df, #224abe); color: white;">
                            <h5 class="modal-title">
                                <i class="fas fa-user-cog mr-2"></i> Editar Usuario – {{ $user->name }}
                            </h5>
                            <button type="button" class="close text-white" data-dismiss="modal">
                                <span>&times;</span>
                            </button>
                        </div>

                        <form action="{{ route('admin.users.updatePermissions', $user->id) }}" method="POST">
                            @csrf

                            <div class="modal-body" style="background: #f8f9fc;">

                                <!-- ROL -->
                                <div class="card shadow-sm mb-3" style="border-radius: 12px;">
                                    <div class="card-body">
                                        <label class="mb-2"><strong>Tipo de Usuario (Rol)</strong></label>
                                        <select name="role" class="form-control"
                                            style="border-radius: 10px; padding: 10px;">
                                            <option value="">Sin rol</option>

                                            @foreach ($roles as $role)
                                                <option value="{{ $role->name }}"
                                                    @if ($user->roles->contains('name', $role->name)) selected @endif>
                                                    {{ ucfirst($role->name) }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>

                                <!-- PERMISOS -->
                                <div class="card shadow-sm" style="border-radius: 12px;">
                                    <div class="card-body">

                                        <label><strong>Permisos individuales</strong></label>

                                        <div style="max-height: 260px; overflow-y: auto; padding-right: 8px;">
                                            <div class="row mt-2">

                                                @foreach ($permissions as $perm)
                                                    <div class="col-md-6 mb-2">
                                                        <div class="custom-control custom-checkbox">
                                                            @php
                                                                // Permisos que el usuario ya tiene (directos o heredados del rol)
                                                                $userPermissions = $user
                                                                    ->getAllPermissions()
                                                                    ->pluck('name')
                                                                    ->toArray();
                                                            @endphp

                                                            <input type="checkbox" class="custom-control-input"
                                                                id="perm{{ $user->id }}{{ $perm->id }}"
                                                                name="permissions[]" value="{{ $perm->name }}"
                                                                @if (in_array($perm->name, $userPermissions)) checked @endif>
                                                            <label class="custom-control-label"
                                                                for="perm{{ $user->id }}{{ $perm->id }}">
                                                                {{ $perm->name }}
                                                            </label>
                                                        </div>
                                                    </div>
                                                @endforeach



                                            </div>
                                        </div>

                                    </div>
                                </div>

                            </div>

                            <!-- FOOTER -->
                            <div class="modal-footer" style="background: #f1f3f9;">
                                <button type="button" class="btn btn-secondary" style="border-radius: 10px;"
                                    data-dismiss="modal">
                                    <i class="fas fa-times mr-1"></i> Cerrar
                                </button>

                                <button type="submit" class="btn btn-success" style="border-radius: 10px;">
                                    <i class="fas fa-save mr-1"></i> Guardar Cambios
                                </button>
                            </div>

                        </form>

                    </div>
                </div>
            </div>
        @endforeach


    </div>
@stop


@section('css')
    <style>
        .modal-body::-webkit-scrollbar {
            width: 8px;
        }

        .modal-body::-webkit-scrollbar-thumb {
            background: #b4c3ff;
            border-radius: 10px;
        }

        .modal-body::-webkit-scrollbar-thumb:hover {
            background: #8da3ff;
        }
    </style>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.3/css/select2.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css" rel="stylesheet">
@stop
@section('js')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.3/js/select2.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.all.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js@3.8.0/dist/chart.min.js"></script>


@stop
