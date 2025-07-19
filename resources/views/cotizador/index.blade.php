@extends('layouts.app')

@section('title', 'Dashboard')

@section('content')

    {{-- @livewire('cotizador.cotizador-llantas') --}}

    <div class="page-wrapper">
        {{-- Encabezado --}}
        <div class="page-header d-print-none">
            <div class="container-xl">
                <div class="row g-2 align-items-center">
                    <div class="col">
                        <div class="page-pretitle">
                            <ol class="breadcrumb" aria-label="breadcrumbs">
                                <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Inicio</a></li>
                                <li class="breadcrumb-item active" aria-current="page"><a href="#">Cotizador</a>
                                </li>
                            </ol>
                        </div>
                        <h2 class="page-title">
                            Cotizador
                        </h2>
                    </div>

                    <div class="col-auto ms-auto d-print-none">
                        <div class="btn-list">
                            <a href="#" class="btn btn-primary d-none d-md-inline-block" data-bs-toggle="modal"
                                data-bs-target="#modalTraspaso">
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                                    fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                    stroke-linejoin="round"
                                    class="icon icon-tabler icons-tabler-outline icon-tabler-download">
                                    <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                    <path d="M4 17v2a2 2 0 0 0 2 2h12a2 2 0 0 0 2 -2v-2" />
                                    <path d="M7 11l5 5l5 -5" />
                                    <path d="M12 4l0 12" />
                                </svg>
                                Descargar cotizador
                            </a>
                            <a href="#" class="btn btn-primary d-md-none btn-icon" data-bs-toggle="modal"
                                data-bs-target="#modalTraspaso">
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                                    fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                    stroke-linejoin="round"
                                    class="icon icon-tabler icons-tabler-outline icon-tabler-download">
                                    <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                    <path d="M4 17v2a2 2 0 0 0 2 2h12a2 2 0 0 0 2 -2v-2" />
                                    <path d="M7 11l5 5l5 -5" />
                                    <path d="M12 4l0 12" />
                                </svg>
                            </a>



                        </div>


                    </div>
                </div>
            </div>
        </div>

        {{-- Cuerpo --}}
        <div class="page-body">
            <div class="container-xl">
                <div class="row g-2">
                    <div class="col-12 bg-white px-3 py-2">
                        <div
                            class=" d-sm-inline d-lg-flex flex-column flex-md-row align-items-center justify-content-between">
                            <div class="row mb-sm-2 mb-xl-0">
                                <label class="col-2 col-form-label">Ancho</label>
                                <div class="col">
                                    <input type="number" placeholder="175" name="ancho" class="form-control">
                                </div>
                            </div>
                            <div class="row mb-sm-2 mb-xl-0">
                                <label class="col-2 col-form-label">Alto</label>
                                <div class="col">
                                    <input type="number" placeholder="60" name="alto" class="form-control">
                                </div>
                            </div>
                            <div class="row mb-sm-2 mb-xl-0">
                                <label class="col-2 col-form-label">Rin</label>
                                <div class="col">
                                    <input type="number" placeholder="14" name="rin" class="form-control">
                                </div>
                            </div>
                            <div class="row mb-sm-3 mb-xl-0">
                                <label class="col-2 col-form-label">Marca</label>
                                <div class="col">
                                    <input type="text" placeholder="TOYO" name="marca" class="form-control">
                                </div>
                            </div>

                            <div>
                                <button type="submit" class="btn btn-success w-100">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                        viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                        stroke-linecap="round" stroke-linejoin="round"
                                        class="icon icon-tabler icons-tabler-outline icon-tabler-adjustments">
                                        <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                        <path d="M4 10a2 2 0 1 0 4 0a2 2 0 0 0 -4 0" />
                                        <path d="M6 4v4" />
                                        <path d="M6 12v8" />
                                        <path d="M10 16a2 2 0 1 0 4 0a2 2 0 0 0 -4 0" />
                                        <path d="M12 4v10" />
                                        <path d="M12 18v2" />
                                        <path d="M16 7a2 2 0 1 0 4 0a2 2 0 0 0 -4 0" />
                                        <path d="M18 4v1" />
                                        <path d="M18 9v11" />
                                    </svg>
                                    Aplicar filtros
                                </button>

                            </div>
                        </div>
                    </div>


                    <div class="card">
                        <div class="table-responsive px-1">
                            <table id="tablaInventario" class="table card-table table-vcenter">
                                <thead>
                                    <tr>
                                        <th>Artículo</th>
                                        <th>Descripción</th>
                                        <th>Aplicación</th>
                                        <th>OE</th>
                                        <th>Equivalente</th>
                                        <th>Marca</th>
                                        <th>Promoción</th>

                                        {{-- Precios --}}
                                        <th>Semi - Mayoreo</th>
                                        <th>Mayoreo</th>
                                        <th>Promoción del Mes</th>
                                        <th>NK</th>
                                        <th>Pronto Pago</th>

                                        {{-- Ubicaciones --}}
                                        <th>Matriz</th>
                                        <th>Vicente Guerrero</th>
                                        <th>Vallejo</th>
                                        <th>Poniente</th>
                                        <th>Ixtapaluca</th>
                                        <th>Querétaro</th>
                                        <th>Guadalajara</th>
                                        <th>Obregón</th>
                                        <th>Obregón mayoreo</th>
                                        <th>Jojutla</th>
                                        <th>Plásticos</th>
                                        <th>Jorges</th>
                                        <th>Misael</th>
                                        <th>Chamilpa</th>
                                        <th>Ahuatepec</th>
                                        <th>Vinilos</th>
                                        <th>Bodega de camión</th>
                                    </tr>
                                </thead>
                            </table>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>

@endsection

@section('scripts')
    <script>
        $(function() {
            $('#tablaInventario').DataTable({
                processing: true,
                serverSide: true,
                scrollX: true, // Habilita scroll horizontal
                ajax: {
                    url: '{{ route('cotizador.data') }}',
                    dataSrc: 'data'
                },
                columns: [{
                        data: 'itemid'
                    },
                    {
                        data: 'descripcion'
                    },
                    {
                        data: 'aplicacion'
                    },
                    {
                        data: 'oe'
                    },
                    {
                        data: 'medida_equivalente'
                    },
                    {
                        data: 'marca'
                    },
                    {
                        data: 'promocion'
                    },
                    {
                        data: 'semi_mayoreo'
                    },
                    {
                        data: 'mayoreo'
                    },
                    {
                        data: 'promocion_del_mes'
                    },
                    {
                        data: 'nk'
                    },
                    {
                        data: 'promocion_por_pronto_pago'
                    },
                    {
                        data: 'matriz'
                    },
                    {
                        data: 'vicente_guerrero'
                    },
                    {
                        data: 'vallejo'
                    },
                    {
                        data: 'poniente'
                    },
                    {
                        data: 'ixtapaluca'
                    },
                    {
                        data: 'queretaro'
                    },
                    {
                        data: 'guadalajara'
                    },
                    {
                        data: 'obregon'
                    },
                    {
                        data: 'obregon_mayoreo'
                    },
                    {
                        data: 'jojutla'
                    },
                    {
                        data: 'plasticos'
                    },
                    {
                        data: 'jorges'
                    },
                    {
                        data: 'misael'
                    },
                    {
                        data: 'chamilpa'
                    },
                    {
                        data: 'ahuatepec'
                    },
                    {
                        data: 'vinilos'
                    },
                    {
                        data: 'bodega_de_camion'
                    }
                ],
                pageLength: 5,
                order: [
                    [0, 'asc']
                ],
                language: {
                    url: "https://cdn.datatables.net/plug-ins/1.13.5/i18n/es-MX.json",
                    paginate: {
                        first: "«",
                        last: "»",
                        next: "Sig.",
                        previous: "Ant."
                    }
                },
                fixedColumns: {
                    left: 2 // Congela las primeras 2 columnas
                }
            });
        });
    </script>
@endsection
