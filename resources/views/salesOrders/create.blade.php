@extends('layouts.app')

@section('title', 'Dashboard')

@section('content')

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
                        Generar Orden de Venta
                    </h2>
                </div>

                <div class="col-auto ms-auto d-print-none">
                    <div class="btn-list">
                        <button form="myForm" href="#" class="btn btn-cyan d-none d-md-inline-block" data-bs-toggle="modal"
                            data-bs-target="#modalTraspaso">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"
                                stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                                class="icon icon-tabler icons-tabler-outline icon-tabler-circle-plus">
                                <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                <path d="M3 12a9 9 0 1 0 18 0a9 9 0 0 0 -18 0" />
                                <path d="M9 12h6" />
                                <path d="M12 9v6" />
                            </svg>
                            Generar Orden de Venta
                        </button>
                        <a href="#" class="btn btn-cyan d-md-none btn-icon">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"
                                stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
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
                    <div class="card">
                        <div class="card-body">
                            <div class="row g-2 align-items-center">

                                <form id="myForm" action="{{ route('salesOrder.store') }}" method="POST">
                                    @csrf

                                    <div class="row">
                                        <div class="col-6">
                                            <label for="customer">Buscar cliente</label>
                                            <input id="customer" name="customer_id" placeholder="Buscar Cliente..."
                                                class="form-control" required>
                                        </div>


                                        <div class="col-3">
                                            <label for="customer">Cliente ID</label>
                                            <input type="text" id="customer_id" name="customer_id" class="form-control"
                                                readonly required>
                                        </div>
                                        <div class="col-3">
                                            <label for="customer">Cliente Nombre</label>
                                            <input type="text" id="customer_name" name="customer_name" class="form-control"
                                                readonly required>
                                        </div>

                                        <div class="col-3">
                                            <label for="customer">Cliente RFC</label>
                                            <input type="text" id="customer_rfc" name="customer_rfc" class="form-control"
                                                readonly required>
                                        </div>

                                        <div class="col-3">
                                            <label for="customer">Fecha de Transacción</label>

                                            <input type="date" id="tranDate" name="tranDate" class="form-control" readonly
                                                required>
                                        </div>

                                        <div class="col-3">
                                            <label for="customer">Buscar Ubicacion</label>

                                            <input id="locations" name="location_id" placeholder="Buscar Ubicacion..."
                                                class="form-control" required>
                                        </div>
                                        <div class="col-3">
                                            <label for="customer">Ubicacion ID</label>
                                            <input type="text" id="location_id" name="location_id" class="form-control"
                                                readonly required>
                                        </div>
                                        <div class="col-3">
                                            <label for="customer">Ubicacion Nombre</label>

                                            <input type="text" id="location_name" name="location_name" class="form-control"
                                                readonly required>
                                        </div>


                                    </div>

                                    <!-- Puedes seguir agrupando por secciones -->
                                    <div class="row mt-3">
                                        <div class="col-4">
                                            <label for="custbody_cfdi_formadepago">Forma de Pago</label>
                                            <select id="custbody_cfdi_formadepago" name="custbody_cfdi_formadepago"
                                                required>
                                                <option value="" readonly selected hidden>Selecciona una opción</option>
                                                <option value="1">PUE-PAGO EN UNA SOLA EXHIBICION</option>
                                                <option value="2">PIP-Pago inicial y parcialidades</option>
                                                <option value="3">PPD-Pago en parcialidades o diferido</option>
                                            </select>
                                        </div>

                                        <div class="col-4">
                                            <label for="custbody_cfdi_metpago_sat">Método de Pago</label>
                                            <select id="custbody_cfdi_metpago_sat" name="custbody_cfdi_metpago_sat"
                                                required>
                                                <option value="" readonly selected hidden>Selecciona una opción</option>
                                                <option value="1">01 - EFECTIVO</option>
                                                <option value="7">02 - CHEQUE</option>
                                                <option value="8">03 - TRANSFERENCIA</option>
                                                <option value="2">04 - TARJETA DE CRÉDITO</option>
                                                <option value="12">05 - MONEDERO ELECTRONICO</option>
                                                <option value="13">06 - DINERO ELECTRONICO</option>
                                                <option value="14">08 - VALES DE DESPENSA</option>
                                                <option value="15">12 - DACION EN PAGO</option>
                                                <option value="16">13 - PAGO POR SUBROGACION</option>
                                                <option value="17">14 - PAGO POR CONSIGNACION</option>
                                                <option value="11">17 - COMPENSACION</option>
                                                <option value="3">28 - TARJETA DE DÉBITO</option>
                                                <option value="19">30 - APLICACIÓN DE ANTICIPOS</option>
                                                <option value="18">31 - INTERMEDIARIO PAGOS</option>
                                                <option value="10">99 - POR DEFINIR</option>
                                            </select>
                                        </div>

                                        <div class="col-4">
                                            <label for="custbody_uso_cfdi">Forma de Pago</label>
                                            <select id="custbody_uso_cfdi" name="custbody_uso_cfdi" required>
                                                <option value="" readonly selected hidden>Selecciona una opción</option>
                                                <option value="12">D01-Honorarios médicos, dentales y gastos hospitalarios.
                                                </option>
                                                <option value="13">D02-Gastos médicos por incapacidad o discapacidad
                                                </option>
                                                <option value="14">D03-Gastos funerales.</option>
                                                <option value="15">D04-Donativos.</option>
                                                <option value="16">D05-Intereses reales efectivamente pagados por créditos
                                                    hipotecarios (casa habitación).</option>
                                                <option value="17">D06-Aportaciones voluntarias al SAR.</option>
                                                <option value="18">D07-Primas por seguros de gastos médicos.</option>
                                                <option value="19">D08-Gastos de transportación escolar obligatoria.
                                                </option>
                                                <option value="20">D09-Depósitos en cuentas para el ahorro, primas que
                                                    tengan como
                                                    base planes de pensiones.</option>
                                                <option value="21">D10-Pagos por servicios educativos (colegiaturas)
                                                </option>
                                                <option value="1">G01-Adquisición de mercancias</option>
                                                <option value="2">G02-Devoluciones, descuentos o bonificaciones</option>
                                                <option value="3">G03-Gastos en general</option>
                                                <option value="4">I01-Construcciones</option>
                                                <option value="5">I02-Mobilario y equipo de oficina por inversiones</option>
                                                <option value="6">I03-Equipo de transporte</option>
                                                <option value="7">I04-Equipo de computo y accesorios</option>
                                                <option value="8">I05-Dados, troqueles, moldes, matrices y herramental
                                                </option>
                                                <option value="9">I06-Comunicaciones telefónicas</option>
                                                <option value="10">I07-Comunicaciones satelitales</option>
                                                <option value="11">I08-Otra maquinaria y equipo</option>
                                                <option value="22">P01-Por Definir</option>
                                                <option value="23">S01-Sin efectos fiscales</option>
                                            </select>
                                        </div>

                                        <!-- etc... -->
                                    </div>

                                    <div class="row mt-3">
                                        <div class="col-3">
                                            <label for="memo">Nota de factura</label>

                                            <input type="text" id="memo" name="memo" class="form-control" required>
                                        </div>

                                        <div class="col-3">
                                            <label for="custbody_nso_notas_de_usuario">Nota para aprobación especial</label>

                                            <input type="text" id="custbody_nso_notas_de_usuario"
                                                name="custbody_nso_notas_de_usuario" class="form-control" required>
                                        </div>

                                    </div>
                                    <!-- Otros bloques -->
                                    <div class="row mt-3">
                                        <table class="table table-striped" id="dynamicTable">
                                            <thead class="table-dark">
                                                <tr>
                                                    <th>#Linea</th>
                                                    <th>Articulo</th>
                                                    <th>Cantidad</th>
                                                    <th></th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <!-- Rows will be added here -->
                                            </tbody>
                                        </table>
                                        <div class="col-3">
                                            <a class="btn btn-indigo" onclick="addRow()">Agregar Fila</a>

                                        </div>
                                    </div>
                                </form>









                            </div>
                        </div>
                    </div>



                </div>
            </div>


            <div class="card">
                <div>
                    <!-- <table id="tablaInventario" class="table card-table table-vcenter">
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
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                </table> -->
                </div>
            </div>

        </div>
    </div>
    </div>
    </div>

    {{-- Librerias Tom select --}}
    <script src="https://cdn.jsdelivr.net/npm/tom-select@2.2.2/dist/js/tom-select.complete.min.js"></script>
    <link href="https://cdn.jsdelivr.net/npm/tom-select@2.2.2/dist/css/tom-select.css" rel="stylesheet" />

    {{-- Tom Select para seleccionar el nombre id y rfc del cliente --}}
    <script>
        new TomSelect("#customer", {
            valueField: 'value',
            labelField: 'text',
            searchField: 'text',
            maxItems: 1,
            create: false,

            load: function (query, callback) {
                if (!query.length) return callback();
                fetch(`/netsuite/customers/search?q=${encodeURIComponent(query)}`)
                    .then(response => response.json())
                    .then(callback).catch(() => callback());
            },
            onChange: function (value) {
                const selectedOption = this.options[value];
                if (selectedOption) {
                    document.getElementById('customer_id').value = selectedOption.value;
                    document.getElementById('customer_name').value = selectedOption.text;
                    document.getElementById('customer_rfc').value = selectedOption.rfc;
                }
            }
        });
    </script>

    <script>
        new TomSelect("#locations", {
            valueField: 'id',
            labelField: 'name',
            searchField: 'name',
            maxItems: 1,
            create: false,

            load: function (query, callback) {
                if (!query.length) return callback();
                fetch(`/netsuite/locations/search?q=${encodeURIComponent(query)}`)
                    .then(response => response.json())
                    .then(callback).catch(() => callback());
            },
            onChange: function (value) {
                const selectedOption = this.options[value];
                if (selectedOption) {
                    document.getElementById('location_id').value = selectedOption.id;
                    document.getElementById('location_name').value = selectedOption.name;
                }
            }
        });
    </script>

    {{-- Seleccionar la fecha de hoy para transaction date --}}
    <script>
        const today = new Date().toISOString().split('T')[0];
        document.getElementById('tranDate').value = today;
    </script>

    {{-- Tom Select para seleccionar FORMA DE PAGO --}}
    <script>


        new TomSelect("#custbody_cfdi_formadepago", {
            maxItems: 1,
            create: false,
        });
    </script>

    <script>

        new TomSelect("#custbody_cfdi_metpago_sat", {
            maxItems: 1,
            create: false,
        });
    </script>

    <script>
        new TomSelect("#custbody_uso_cfdi", {
            maxItems: 1,
            create: false,
        });

    </script>


    <script>
        let rowCount = 0;

        function addRow() {
            rowCount++;
            const tableBody = document.getElementById("dynamicTable").getElementsByTagName('tbody')[0];
            const newRow = tableBody.insertRow();

            // Create unique ID for the location input
            const itemInputId = `locations-${rowCount}`;

            newRow.innerHTML = `
                            <td><span class="text-secondary">${rowCount}</span></td>
                            <td><input id="${itemInputId}" name="item_id[]" placeholder="Buscar Articulo..." class="form-control"></td>
                            <td><input type="number" name="cantidad[]" class="form-control" placeholder="Enter email"></td>
                            <td><button type="button" class="btn btn-outline-danger" onclick="removeRow(this)">Eliminar</button></td>
                                                                                                                                                                                                                                            `;

            // Initialize TomSelect for this new input
            new TomSelect(`#${itemInputId}`, {
                valueField: 'id',
                labelField: 'name',
                searchField: 'name',
                maxItems: 1,
                create: false,
                load: function (query, callback) {
                    if (!query.length) return callback();
                    fetch(`/netsuite/items/search?q=${encodeURIComponent(query)}`)
                        .then(response => response.json())
                        .then(callback)
                        .catch(() => callback());
                },
                onChange: function (value) {
                    const selectedOption = this.options[value];
                    if (selectedOption) {
                        // You can do something with the selected option if needed
                        console.log("Selected Item:", selectedOption.name);
                    }
                }
            });
        }

        function removeRow(button) {
            const row = button.closest("tr");
            row.remove();
            updateRowNumbers();
        }

        function updateRowNumbers() {
            const rows = document.querySelectorAll("#dynamicTable tbody tr");
            rows.forEach((row, index) => {
                row.cells[0].innerText = index + 1;
            });
            rowCount = rows.length;
        }
    </script>



@endsection