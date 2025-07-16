@extends('layouts.app')

@section('title', 'Máximos y mínimos')

@section('content')



    <div class="page">
        <div class="page-wrapper">
            {{-- Encabezado --}}
            <div class="page-header d-print-none">
                <div class="container-xl">
                    <div class="row g-2 align-items-center">
                        <div class="col">
                            <div class="page-pretitle">
                                <ol class="breadcrumb" aria-label="breadcrumbs">
                                    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Inicio</a>
                                        /</li>
                                </ol>
                            </div>
                            <h2 class="page-title">
                                Máximos y mínimos
                            </h2>
                        </div>

                        <div class="col-auto ms-auto d-print-none">
                            <div class="btn-list">
                                <a href="{{ route('dashboard') }}" class="btn btn-ghost-primary my-2"> <svg
                                        xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                                        fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                        stroke-linejoin="round"
                                        class="icon icon-tabler icons-tabler-outline icon-tabler-arrow-left">
                                        <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                        <path d="M5 12l14 0" />
                                        <path d="M5 12l6 6" />
                                        <path d="M5 12l6 -6" />
                                    </svg> Volver</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            {{-- Cuerpo --}}
            <div class="page-body">
                <div class="container-xl">
                    <div class="row g-4">
                        <div class="col-md-5">
                            <div class="card">
                                <div class="card-body col-12">
                                    <div class="card-title">
                                        Factores
                                    </div>
                                    <div class="table-responsive">
                                        <table class="table table-vcenter" id="tabla-factor">
                                            <thead>
                                                <tr>
                                                    <th>Día del mes</th>
                                                    <th>Factor</th>
                                                    <th>Últ. actualización</th>
                                                    <th class="w-1"></th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach ($factores as $factor)
                                                    <tr>
                                                        <td>{{ $factor->id }}</td>
                                                        <td class="text-secondary">
                                                            {{ $factor->factor }}
                                                        </td>
                                                        <td class="text-secondary">
                                                            {{ $factor->updated_at->format('d/m/Y') }}
                                                        </td>
                                                        <td>
                                                            <a href="#" class="btn-edit">
                                                                <svg xmlns="http://www.w3.org/2000/svg" width="24"
                                                                    height="24" viewBox="0 0 24 24" fill="none"
                                                                    stroke="currentColor" stroke-width="2"
                                                                    stroke-linecap="round" stroke-linejoin="round"
                                                                    class="icon icon-tabler icons-tabler-outline icon-tabler-edit">
                                                                    <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                                                    <path
                                                                        d="M7 7h-1a2 2 0 0 0 -2 2v9a2 2 0 0 0 2 2h9a2 2 0 0 0 2 -2v-1" />
                                                                    <path
                                                                        d="M20.385 6.585a2.1 2.1 0 0 0 -2.97 -2.97l-8.415 8.385v3h3l8.385 -8.415z" />
                                                                    <path d="M16 5l3 3" />
                                                                </svg>
                                                            </a>
                                                        </td>
                                                    </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-7">
                            <div class="card">
                                <div class="card-body col-12">
                                    <form class="dropzone" id="dropzone-custom" action="{{ route('cargar-datos.store') }}"
                                        autocomplete="off" novalidate>
                                        @csrf
                                        <div class="dz-message">
                                            <h3 class="dropzone-msg-title">Da clic o arrastra tus archivos</h3>
                                            <span class="dropzone-msg-desc">"FUT-MaximosyMinimosMarca"</span><br>
                                            <span class="dropzone-msg-desc">"FUT-MaximosyMinimosRin"</span><br>
                                            <span class="dropzone-msg-desc">"ResultadosFUTExistenciasAux"</span><br><br>
                                            <span class="dropzone-msg-desc">Solo formatos .CSV</span>
                                        </div>
                                    </form>
                                </div>
                                <div class="p-3 text-center">
                                    <a href="{{ route('descargar.maxmin') }}" class="btn btn-outline-success"
                                        id="btn-descargar">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                            viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                            stroke-linecap="round" stroke-linejoin="round"
                                            class="icon icon-tabler icons-tabler-outline icon-tabler-download">
                                            <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                            <path d="M4 17v2a2 2 0 0 0 2 2h12a2 2 0 0 0 2 -2v-2" />
                                            <path d="M7 11l5 5l5 -5" />
                                            <path d="M12 4l0 12" />
                                        </svg>
                                        Descargar
                                    </a>
                                    <h2 id="loader" style="display: none">Subiendo archivos<span
                                            class="animated-dots"></span>
                                    </h2>
                                </div>

                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Modal para editar cada detalle de carga --}}
    <div class="modal fade" id="factorModal" tabindex="-1" aria-labelledby="detalleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-scrollable">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="detalleModalLabel">Editar factor</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form method="POST" class="needs-validation" novalidate>
                        @csrf
                        @method('PUT')
                        <div class="mb-3">
                            <label class="form-label required">Factor</label>
                            <input type="number" step="any" class="form-control" id="modal-factor" name="factor"
                                required>
                            <div class="invalid-feedback text-red">El factor es obligatorio</div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                            <button type="submit" class="btn btn-primary">Editar</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    @endsection


    @section('scripts')

        @if (session('editar') == 'ok')
            <script>
                Swal.fire({
                    title: 'Factor actualizado',
                    text: 'El factor se actualizó correctamente',
                    icon: 'success',
                });
            </script>
        @elseif (session('editar') == 'error')
            <script>
                Swal.fire({
                    title: 'Error',
                    text: 'El factor no se pudo actualizar',
                    icon: 'error',
                });
            </script>
        @endif

        {{-- Tabla de factor --}}
        <script>
            let table = new DataTable('#tabla-factor', {
                language: {
                    url: "https://cdn.datatables.net/plug-ins/1.13.5/i18n/es-MX.json",
                    "oPaginate": {
                        "sFirst": "«",
                        "sLast": "»",
                        "sNext": "Sig.",
                        "sPrevious": "Ant."
                    }
                }
            });

            // Detectar clic en el botón de edición
            $('#tabla-factor').on('click', '.btn-edit', function(e) {
                e.preventDefault();
                // Obtener los datos de la fila
                let rowData = table.row($(this).parents('tr')).data();

                abrirModalFactor(rowData);

                $(this).toggleClass('bg-blue-lt').siblings().removeClass('bg-blue-lt');
            });

            //Función para pasar datos al modal
            function abrirModalFactor(datos) {
                //document.getElementById('modal-id-factor').value = datos[0];
                document.getElementById('modal-factor').value = datos[1];

                // Modificar el action del formulario para incluir el ID en la URL
                let form = document.querySelector('#factorModal form');
                form.action = `/factor/${datos[0]}`; // Esto cambia dinámicamente la URL

                // Mostrar el modal
                const modal = new bootstrap.Modal(document.getElementById('factorModal'));
                modal.show();
            }
        </script>

        {{-- Script para subir archivos --}}
        <script>
            document.addEventListener("DOMContentLoaded", function() {
                Dropzone.autoDiscover = false;
                var dropzone = new Dropzone("#dropzone-custom", {
                    paramName: "archivos[]",
                    maxFiles: 3,
                    acceptedFiles: ".csv",
                    addRemoveLinks: true,
                    dictRemoveFile: "Eliminar",
                    autoProcessQueue: true, // Subida automática
                    init: function() {
                        let dzInstance = this; // Referencia al Dropzone
                        let loader = document.getElementById('loader');
                        let btn_descargar = document.getElementById('btn-descargar');

                        this.on("sending", function() {
                            requestAnimationFrame(() => {
                                loader.style.display = 'block';
                                btn_descargar.style.display = 'none';
                            });
                        });

                        // Ocultar loader cuando todos los archivos han terminado de subirse
                        this.on("queuecomplete", function() {
                            loader.style.display = 'none';
                            btn_descargar.style.display = 'inline-block';

                            Swal.fire({
                                title: "¡Datos cargados!",
                                text: "El reporte esta listo para descargarse",
                                icon: "success",
                                confirmButtonText: "Descargar",
                            }).then(() => {
                                //Iniciar la descarga del reporte
                                window.location.href = "/descargar-maxmin";
                            });

                            // Limpiar Dropzone después de la subida
                            setTimeout(() => {
                                dzInstance.removeAllFiles();
                            }, 3000);
                        });

                        // Evento en caso de error (se dispara por archivo)
                        this.on("error", function(file, response) {
                            loader.style.display = 'none'; // Ocultar loader si hay error
                            let errorMessage = typeof response === "string" ? response : response
                                .error || "Error desconocido";
                            Swal.fire({
                                title: "¡UPS!",
                                text: errorMessage,
                                icon: "error"
                            });
                        });
                    }
                });
            });
        </script>

        {{-- <script>
            document.addEventListener("DOMContentLoaded", function() {
                const dropzone = new Dropzone("#dropzone-custom", {
                    paramName: "archivos[]", // Nombre del archivo en la request
                    acceptedFiles: ".csv, .txt", // Tipos de archivos aceptados
                    headers: {
                        "X-CSRF-TOKEN": "{{ csrf_token() }}" // CSRF para Laravel
                    },
                    autoProcessQueue: true, // Subida automática
                    maxFilesize: 5, // Tamaño máximo en MB
                    parallelUploads: 3, // Número de archivos simultáneos
                    addRemoveLinks: true, // Agregar botón de eliminar
                    dictRemoveFile: "Eliminar", // Texto del botón
                    init: function() {


                        // Evento en caso de éxito
                        this.on("success", function(file, response) {
                            Swal.fire({
                                title: "¡Datos cargados!",
                                text: "Datos cargados exitosamente.",
                                icon: "success"
                            }).then(() => {
                                // Si quieres eliminar los archivos automáticamente después de subirlos
                                setTimeout(() => {
                                    this.removeFile(file);
                                }, 3000); // Se elimina después de 3 segundos
                            });
                        });

                        // Evento en caso de error
                        this.on("error", function(file, response) {
                            let errorMessage = typeof response === "string" ? response : response
                                .error || "Error desconocido";
                            Swal.fire({
                                title: "¡UPS!",
                                text: errorMessage,
                                //text: "Hubo un error al cargar los datos.",
                                icon: "error"
                            }).then(() => {
                                modalInstance.hide(); // Cerrar el modal después del éxito
                            });
                        });

                        // Evento al completar cualquier archivo (éxito o error)
                        this.on("complete", function(file) {
                            this.removeFile(file); // Elimina el archivo de la lista de Dropzone
                        });
                    }
                });
            })
        </script> --}}
    @endsection
