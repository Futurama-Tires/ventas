@extends('layouts.app')

@section('title', 'Cotizador')

@section('content')

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
                            <a class="btn" data-bs-toggle="offcanvas" href="#offcanvasStart" role="button"
                                aria-controls="offcanvasStart">
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                                    fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                    stroke-linejoin="round"
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
                            </a>

                            <a href="{{ route('exportar.inventario') }}" onclick="showLoading()"
                                class="btn btn-primary d-none d-md-inline-block btnExportar">
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

                            <a href="{{ route('exportar.inventario') }}"
                                class="btn btn-primary d-md-none btn-icon btnExportar" onclick="showLoading()">
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

                <div class="alert alert-success alert-dismissible" role="alert" style="display: none;"
                    id="alertDescargaCotizador">
                    <div class="d-flex">
                        <div class="alert-icon">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                                fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                stroke-linejoin="round" class="icon icon-tabler icons-tabler-outline icon-tabler-download">
                                <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                <path d="M4 17v2a2 2 0 0 0 2 2h12a2 2 0 0 0 2 -2v-2" />
                                <path d="M7 11l5 5l5 -5" />
                                <path d="M12 4l0 12" />
                            </svg>
                        </div>
                        <div>
                            <h4 class="alert-heading">Descarga correcta</h4>
                            <div class="alert-description">
                                El cotizador se ha descargado correctamente. Si no se descarga, por favor
                                <a href="{{ route('exportar.inventario') }}" class="alert-link">haz clic aquí</a> para
                                descargarlo nuevamente.
                            </div>
                        </div>
                        <a class="btn-close" data-bs-dismiss="alert" aria-label="close"></a>
                    </div>
                </div>

                <div class="row g-2">

                    <div class="card">
                        <div class="progress progress-sm" id="loadingProgress" style="display: none;">
                            <div class="progress-bar progress-bar-indeterminate bg-green"></div>
                        </div>
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

    {{-- Off canvas para filtros --}}
    <div class="offcanvas offcanvas-start" tabindex="-1" id="offcanvasStart" aria-labelledby="offcanvasStartLabel">
        <div class="offcanvas-header">
            <h2 class="offcanvas-title" id="offcanvasStartLabel">Filtros</h2>
            <button type="button" class="btn-close text-reset" data-bs-dismiss="offcanvas" aria-label="Close"></button>
        </div>
        <div class="offcanvas-body">
            <div>
                <div class="mb-3">
                    <div class="form-label">Ancho</div>
                    <input type="number" placeholder="175" name="ancho" id="ancho" class="form-control">
                </div>

                <div class="mb-3">
                    <div class="form-label">Alto</div>
                    <input type="number" placeholder="60" name="alto" id="alto" class="form-control">
                </div>

                <div class="mb-3">
                    <div class="form-label">Rin</div>
                    <input type="number" placeholder="14" name="rin" id="rin" class="form-control">
                </div>

                <div class="form-label">Marca</div>
                <div class="mb-3">
                    <input type="text" placeholder="TOYO" name="marca" id="marca"
                        class="form-control form-select">
                </div>

                <div class="form-label">Aplicación</div>
                <div class="mb-3">
                    <input type="text" name="aplicacion" id="aplicacion" class="form-control form-select">
                </div>


                <div class="form-label">Nivel de precio</div>
                <div class="mb-3">
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" name="niveles_precio[]" value="MAYOREO"
                            id="mayoreo">
                        <label class="form-check-label" for="mayoreo">MAYOREO</label>
                    </div>
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" name="niveles_precio[]" value="NK"
                            id="nk">
                        <label class="form-check-label" for="nk">NK</label>
                    </div>
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" name="niveles_precio[]"
                            value="PROMOCION DEL MES" id="promo_mes">
                        <label class="form-check-label" for="promo_mes">PROMOCIÓN DEL MES</label>
                    </div>
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" name="niveles_precio[]"
                            value="PROMOCION POR PRONTO PAGO" id="promo_pago">
                        <label class="form-check-label" for="promo_pago">PROMOCIÓN POR PRONTO PAGO</label>
                    </div>
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" name="niveles_precio[]" value="SEMI - MAYOREO"
                            id="semi_mayoreo">
                        <label class="form-check-label" for="semi_mayoreo">SEMI - MAYOREO</label>
                    </div>
                </div>

                <div class="form-label">Ubicaciones</div>
                <div class="mb-3">
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" name="ubicaciones[]" value="1"
                            id="matriz">
                        <label class="form-check-label" for="matriz">MATRIZ</label>
                    </div>
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" name="ubicaciones[]" value="2"
                            id="vgr">
                        <label class="form-check-label" for="vgr">VICENTE GUERRERO</label>
                    </div>
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" name="ubicaciones[]" value="4"
                            id="vallejo">
                        <label class="form-check-label" for="vallejo">VALLEJO</label>
                    </div>
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" name="ubicaciones[]" value="76"
                            id="poniente">
                        <label class="form-check-label" for="poniente">PONIENTE</label>
                    </div>
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" name="ubicaciones[]" value="7"
                            id="ixtapaluca">
                        <label class="form-check-label" for="ixtapaluca">IXTAPALUCA</label>
                    </div>
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" name="ubicaciones[]" value="9"
                            id="qro">
                        <label class="form-check-label" for="qro">QUERÉTARO</label>
                    </div>
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" name="ubicaciones[]" value="8"
                            id="gdl">
                        <label class="form-check-label" for="gdl">GUADALAJARA</label>
                    </div>
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" name="ubicaciones[]" value="3"
                            id="obr">
                        <label class="form-check-label" for="obr">OBREGÓN</label>
                    </div>
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" name="ubicaciones[]" value="14"
                            id="obrmay">
                        <label class="form-check-label" for="obrmay">OBREGÓN MAYOREO</label>
                    </div>
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" name="ubicaciones[]" value="6"
                            id="joj">
                        <label class="form-check-label" for="joj">JOJUTLA</label>
                    </div>
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" name="ubicaciones[]" value="13"
                            id="plasticos">
                        <label class="form-check-label" for="plasticos">PLÁSTICOS</label>
                    </div>
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" name="ubicaciones[]" value="11"
                            id="jor">
                        <label class="form-check-label" for="jor">JORGES</label>
                    </div>
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" name="ubicaciones[]" value="12"
                            id="misael">
                        <label class="form-check-label" for="misael">MISAEL</label>
                    </div>
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" name="ubicaciones[]" value="53"
                            id="chamilpa">
                        <label class="form-check-label" for="chamilpa">CHAMILPA</label>
                    </div>
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" name="ubicaciones[]" value="64"
                            id="ahuatepec">
                        <label class="form-check-label" for="ahuatepec">AHUATEPEC</label>
                    </div>
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" name="ubicaciones[]" value="52"
                            id="vinilos">
                        <label class="form-check-label" for="vinilos">VINILOS</label>
                    </div>
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" name="ubicaciones[]" value="59"
                            id="bdc">
                        <label class="form-check-label" for="bdc">BODEGA DE CAMIÓN</label>
                    </div>
                </div>

                <div class="mt-4">
                    <button class="btn btn-success w-100" id="btnBuscar">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                            fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                            stroke-linejoin="round" class="icon icon-tabler icons-tabler-outline icon-tabler-search">
                            <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                            <path d="M10 10m-7 0a7 7 0 1 0 14 0a7 7 0 1 0 -14 0" />
                            <path d="M21 21l-6 -6" />
                        </svg>
                        Buscar</button>

                    <a href="#" class="btn btn-link w-100 mt-1" id="btnReset"><svg
                            xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                            fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                            stroke-linejoin="round" class="icon icon-tabler icons-tabler-outline icon-tabler-x">
                            <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                            <path d="M18 6l-12 12" />
                            <path d="M6 6l12 12" />
                        </svg> Borrar filtros</a>
                </div>
            </div>
        </div>
    </div>

@endsection

@section('scripts')
    {{-- Script para desplegar las marcas con Tom Select --}}
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const marcas = [
                'ALLIANCE', 'ANNAITE', 'ANTARES', 'ASCENSO', 'ATLAS', 'AUSTONE',
                'BLACKHAWK', 'BRIDGESTONE', 'BRIGHTWAY', 'BROAD PEAK', 'CACHLAND',
                'CARAWAY', 'DAVANTI', 'HAIDA', 'HULPAC', 'KUMHO', 'PIRELLI',
                'POWER KING', 'SKYFIRE', 'TOYO', 'TITAN', 'TORNEL', 'CHENGSHAN',
                'CONTINENTAL', 'COOPER', 'SPORTRAK', 'MARSHAL', 'TORQUE', 'ZETUM',
                'YOKOHAMA', 'AMBERSTONE', 'FIRESTONE', 'DURABLE', 'ECOMASTER',
                'EMPIRE', 'MINERVA', 'FORERUNNER', 'FRONWAY', 'FULLBORE',
                'FULLRUN', 'GOALSTAR', 'GOODYEAR', 'GRANDSTONE', 'HIFLY',
                'ILINK', 'INSTRASUPER', 'INTRASUPER', 'KAPSEN', 'KEBEK',
                'KPATOS', 'LING LONG', 'MACROYAL', 'ONYX', 'MASSIMO',
                'MASTERCRAFT', 'MICHELIN', 'MILEVER', 'MIRAGE', 'NEXEN',
                'OVATION', 'PHYRON', 'PROLOAD', 'RACEALONE', 'ROADMASTER',
                'ROADSHINE', 'SAILUN', 'SEIBERLING', 'SURETRAC', 'TOEE',
                'STARFIRE', 'STORMER', 'SUNFULL', 'SUNNY', 'SUPER MEALLIR',
                'TECHSHIELD', 'TERRAKING', 'TOLEDO', 'VOLTYRE', 'WINRUN',
                'WOSEN', 'YEADA'
            ];

            const select = new TomSelect('#marca', {
                options: marcas.map(marca => ({
                    value: marca,
                    text: marca
                })),
                create: false,
                maxItems: 1, // Esto limita a seleccionar solo un elemento
                maxOptions: true,
                searchField: 'text',
                placeholder: 'TOYO',
                allowEmptyOption: false,
                render: {
                    option: function(data, escape) {
                        return '<div>' + escape(data.text) + '</div>';
                    },
                    no_results: function(data, escape) {
                        return '<div class="no-results">No se encontraron resultados para "' + escape(
                            data.input) + '"</div>';
                    }
                }
            });
        });
    </script>

    {{-- Script para las aplicaciones --}}
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const aplicaciones = [
                'AGRICOLA', 'ALL TERRAIN', 'CAMARA', 'CAMIÓN', 'CARGA', 'CARRETERA',
                'CONVENCIONAL', 'CORBATA', 'DIRECCIÓN', 'INDUSTRIAL', 'MINERA',
                'MIXTA', 'MOTOCICLETA', 'MT', 'MUD TERRAIN', 'RIN', 'ROUGH TERRAIN',
                'TODA POSICIÓN', 'TRACCIÓN',
            ];

            const select = new TomSelect('#aplicacion', {
                options: aplicaciones.map(aplicacion => ({
                    value: aplicacion.normalize('NFD').replace(/[\u0300-\u036f]/g,
                        ""), // Normaliza caracteres acentuados
                    text: aplicacion
                })),
                create: false,
                maxItems: 3, // Esto limita a seleccionar solo un elemento
                maxOptions: true,
                plugins: ['remove_button'],
                searchField: 'text',
                placeholder: 'Seleccione aplicación (máx. 3)',
                allowEmptyOption: false,
                render: {
                    option: function(data, escape) {
                        return '<div>' + escape(data.text) + '</div>';
                    },
                    no_results: function(data, escape) {
                        return '<div class="no-results">No se encontraron resultados para "' + escape(
                            data.input) + '"</div>';
                    }
                },
            });
        });
    </script>

    {{-- Script para cargar la tabla --}}
    <script>
        $(function() {
            const tabla = $('#tablaInventario').DataTable({
                processing: true,
                serverSide: true,
                scrollX: true, // Habilita scroll horizontal
                ajax: {
                    url: '{{ route('cotizador.data') }}',
                    data: function(d) {
                        d.alto = $('#alto').val();
                        d.ancho = $('#ancho').val();
                        d.rin = $('#rin').val();
                        d.marca = $('#marca').val();
                        d.aplicacion = $('#aplicacion').val(); // Tom Select ya maneja múltiples valores
                        d.niveles_precio = $('input[name="niveles_precio[]"]:checked').map(function() {
                            return this.value;
                        }).get();

                        d.ubicaciones = $('input[name="ubicaciones[]"]:checked').map(function() {
                            return this.value;
                        }).get();
                    },
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
                        data: 'semi_mayoreo',
                        render: function(data, type, row) {
                            if (type === 'display') {
                                return data ? '$' + parseFloat(data).toLocaleString('es-MX', {
                                    minimumFractionDigits: 2,
                                    maximumFractionDigits: 2
                                }) : 'NO SE PUEDO OBTENER';
                            }
                            return data;
                        }
                    },
                    {
                        data: 'mayoreo',
                        render: function(data, type, row) {
                            if (type === 'display') {
                                return data ? '$' + parseFloat(data).toLocaleString('es-MX', {
                                    minimumFractionDigits: 2,
                                    maximumFractionDigits: 2
                                }) : 'NO SE PUEDO OBTENER';
                            }
                            return data;
                        }
                    },
                    {
                        data: 'promocion_del_mes',
                        render: function(data, type, row) {
                            if (type === 'display') {
                                return data ? '$' + parseFloat(data).toLocaleString('es-MX', {
                                    minimumFractionDigits: 2,
                                    maximumFractionDigits: 2
                                }) : 'NO SE PUEDO OBTENER';
                            }
                            return data;
                        }
                    },
                    {
                        data: 'nk',
                        render: function(data, type, row) {
                            if (type === 'display') {
                                return data ? '$' + parseFloat(data).toLocaleString('es-MX', {
                                    minimumFractionDigits: 2,
                                    maximumFractionDigits: 2
                                }) : 'NO SE PUEDO OBTENER';
                            }
                            return data;
                        }
                    },
                    {
                        data: 'promocion_por_pronto_pago',
                        render: function(data, type, row) {
                            if (type === 'display') {
                                return data ? '$' + parseFloat(data).toLocaleString('es-MX', {
                                    minimumFractionDigits: 2,
                                    maximumFractionDigits: 2
                                }) : 'NO SE PUEDO OBTENER';
                            }
                            return data;
                        }
                    },
                    {
                        data: 'matriz',
                        render: function(data, type, row) {
                            if (data > 0) {
                                return `<span class="text-green fw-bold">${data}</span>`;
                            }
                            return `<span class="text-orange">${data}</span>`;
                        }
                    },
                    {
                        data: 'vicente_guerrero',
                        render: function(data, type, row) {
                            if (data > 0) {
                                return `<span class="text-green fw-bold">${data}</span>`;
                            }
                            return `<span class="text-orange">${data}</span>`;
                        }
                    },
                    {
                        data: 'vallejo',
                        render: function(data, type, row) {
                            if (data > 0) {
                                return `<span class="text-green fw-bold">${data}</span>`;
                            }
                            return `<span class="text-orange">${data}</span>`;
                        }
                    },
                    {
                        data: 'poniente',
                        render: function(data, type, row) {
                            if (data > 0) {
                                return `<span class="text-green fw-bold">${data}</span>`;
                            }
                            return `<span class="text-orange">${data}</span>`;
                        }
                    },
                    {
                        data: 'ixtapaluca',
                        render: function(data, type, row) {
                            if (data > 0) {
                                return `<span class="text-green fw-bold">${data}</span>`;
                            }
                            return `<span class="text-orange">${data}</span>`;
                        }
                    },
                    {
                        data: 'queretaro',
                        render: function(data, type, row) {
                            if (data > 0) {
                                return `<span class="text-green fw-bold">${data}</span>`;
                            }
                            return `<span class="text-orange">${data}</span>`;
                        }
                    },
                    {
                        data: 'guadalajara',
                        render: function(data, type, row) {
                            if (data > 0) {
                                return `<span class="text-green fw-bold">${data}</span>`;
                            }
                            return `<span class="text-orange">${data}</span>`;
                        }
                    },
                    {
                        data: 'obregon',
                        render: function(data, type, row) {
                            if (data > 0) {
                                return `<span class="text-green fw-bold">${data}</span>`;
                            }
                            return `<span class="text-orange">${data}</span>`;
                        }
                    },
                    {
                        data: 'obregon_mayoreo',
                        render: function(data, type, row) {
                            if (data > 0) {
                                return `<span class="text-green fw-bold">${data}</span>`;
                            }
                            return `<span class="text-orange">${data}</span>`;
                        }
                    },
                    {
                        data: 'jojutla',
                        render: function(data, type, row) {
                            if (data > 0) {
                                return `<span class="text-green fw-bold">${data}</span>`;
                            }
                            return `<span class="text-orange">${data}</span>`;
                        }
                    },
                    {
                        data: 'plasticos',
                        render: function(data, type, row) {
                            if (data > 0) {
                                return `<span class="text-green fw-bold">${data}</span>`;
                            }
                            return `<span class="text-orange">${data}</span>`;
                        }
                    },
                    {
                        data: 'jorges',
                        render: function(data, type, row) {
                            if (data > 0) {
                                return `<span class="text-green fw-bold">${data}</span>`;
                            }
                            return `<span class="text-orange">${data}</span>`;
                        }
                    },
                    {
                        data: 'misael',
                        render: function(data, type, row) {
                            if (data > 0) {
                                return `<span class="text-green fw-bold">${data}</span>`;
                            }
                            return `<span class="text-orange">${data}</span>`;
                        }
                    },
                    {
                        data: 'chamilpa',
                        render: function(data, type, row) {
                            if (data > 0) {
                                return `<span class="text-green fw-bold">${data}</span>`;
                            }
                            return `<span class="text-orange">${data}</span>`;
                        }
                    },
                    {
                        data: 'ahuatepec',
                        render: function(data, type, row) {
                            if (data > 0) {
                                return `<span class="text-green fw-bold">${data}</span>`;
                            }
                            return `<span class="text-orange">${data}</span>`;
                        }
                    },
                    {
                        data: 'vinilos',
                        render: function(data, type, row) {
                            if (data > 0) {
                                return `<span class="text-green fw-bold">${data}</span>`;
                            }
                            return `<span class="text-orange">${data}</span>`;
                        }
                    },
                    {
                        data: 'bodega_de_camion',
                        render: function(data, type, row) {
                            if (data > 0) {
                                return `<span class="text-green fw-bold">${data}</span>`;
                            }
                            return `<span class="text-orange">${data}</span>`;
                        }
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

            // Botón para aplicar filtros
            $('#btnBuscar').on('click', function() {
                tabla.ajax.reload();

                // Cerrar el offcanvas
                var offcanvasElement = document.getElementById('offcanvasStart');
                var offcanvasInstance = bootstrap.Offcanvas.getInstance(offcanvasElement);
                offcanvasInstance.hide();
            });

            // Botón para resetear filtros
            $('#btnReset').on('click', function() {
                $('#alto').val('');
                $('#ancho').val('');
                $('#rin').val('');

                // Resetear tom-select (marca)
                var marcaTomSelect = $('#marca')[0].tomselect;
                if (marcaTomSelect) {
                    marcaTomSelect.clear();
                }

                // Resetear tom-select (aplicación)
                var aplicacionTomSelect = $('#aplicacion')[0].tomselect;
                if (aplicacionTomSelect) {
                    aplicacionTomSelect.clear();
                }

                // Resetear checkboxes de niveles de precio
                $('input[name="niveles_precio[]"]').prop('checked', false);

                // Resetear checkboxes de ubicaciones
                $('input[name="ubicaciones[]"]').prop('checked', false);

                // Cerrar el offcanvas
                var offcanvasElement = document.getElementById('offcanvasStart');
                var offcanvasInstance = bootstrap.Offcanvas.getInstance(offcanvasElement);
                offcanvasInstance.hide();

                tabla.ajax.reload();

                // Deshabilitar botones después de resetear
                verificarFiltros();
            });

            // Event listeners para cambios en los filtros
            $('#alto, #ancho, #rin, #aplicacion, #marca').on('change input', verificarFiltros);
            $('input[name="niveles_precio[]"]').on('change', verificarFiltros);
            $('input[name="ubicaciones[]"]').on('change', verificarFiltros);

            // Verificar estado inicial al cargar la página
            $(document).ready(function() {
                verificarFiltros();
            });

            function verificarFiltros() {
                // Obtener valores de los filtros
                const alto = $('#alto').val();
                const ancho = $('#ancho').val();
                const rin = $('#rin').val();
                const marca = $('#marca').val();
                const aplicacion = $('#aplicacion').val();
                const checksMarcados = $('input[name="niveles_precio[]"]:checked').length > 0;
                const checksUbiMarcados = $('input[name="ubicaciones[]"]:checked').length > 0;

                // Verificar si algún filtro tiene valor
                const hayFiltros = alto || ancho || rin || marca || aplicacion || checksMarcados ||
                    checksUbiMarcados;

                // Habilitar/deshabilitar botones
                $('#btnBuscar').prop('disabled', !hayFiltros);
                if (hayFiltros) {
                    $('#btnReset').removeClass('disabled');
                } else {
                    $('#btnReset').addClass('disabled');
                }

            }
        });
    </script>

    <script>
        $(document).ready(function() {
            $('.btnExportar').on('click', function() {
                $('#loadingProgress').show();
                $('.btnExportar').addClass('disabled');

                $('#alertDescargaCotizador').hide();
                window.addEventListener('focus', function() {
                    $('#loadingProgress').hide();
                    $('.btnExportar').removeClass('disabled');

                    $('#alertDescargaCotizador').show();
                }, {
                    once: true
                });
            });
        });
    </script>
@endsection
