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
                        <a href="#" class="btn btn-primary d-none d-md-inline-block" data-bs-toggle="modal"
                            data-bs-target="#modalTraspaso">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"
                                stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
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
                                <div class="col-6">
                                    <label for="customer">Buscar cliente</label>
                                    <input id="customer" name="customer_id" placeholder="Buscar Cliente..."
                                        class="form-control">
                                </div>


                                <div class="col-3">
                                    <label for="customer">Cliente ID</label>
                                    <input type="text" id="customer_id" name="customer_id" class="form-control" disabled>
                                </div>
                                <div class="col-3">
                                    <label for="customer">Cliente Nombre</label>
                                    <input type="text" id="customer_name" name="customer_name" class="form-control"
                                        disabled>
                                </div>

                                <div class="col-3">
                                    <label for="customer">Cliente RFC</label>
                                    <input type="text" id="customer_rfc" name="customer_rfc" class="form-control" disabled>
                                </div>

                                <div class="col-3">
                                    <label for="customer">Fecha de Transacción</label>

                                    <input type="date" id="tranDate" name="tranDate" class="form-control">
                                </div>

                                <div class="col-3">
                                    <label for="customer">Buscar Ubicacion</label>

                                    <input id="locations" name="location_id" placeholder="Buscar Ubicacion..."
                                        class="form-control">
                                </div>
                                <div class="col-3">
                                    <label for="customer">Ubicacion ID</label>
                                    <input type="text" id="location_id" name="location_id" class="form-control" disabled>
                                </div>
                                <div class="col-3">
                                    <label for="customer">Ubicacion Nombre</label>

                                    <input type="text" id="location_name" name="location_name" class="form-control"
                                        disabled>
                                </div>

                                <div class="col-3">
                                    <label for="note">Nota</label>

                                    <input type="text" id="note" name="note" class="form-control">
                                </div>

                                <table class="table table-bordered table-striped" id="dynamicTable">
                                    <thead class="table-dark">
                                        <tr>
                                            <th>#</th>
                                            <th>Articulo</th>
                                            <th>Cantidad</th>
                                            <th>Eliminar</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <!-- Rows will be added here -->
                                    </tbody>
                                </table>
                                <button class="btn btn-primary" onclick="addRow()">Add Row</button>

                            </div>
                        </div>
                    </div>


                    <div>
                        <button type="" class="btn btn-success w-100">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"
                                stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
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

    <script>
        let rowCount = 0;

        function addRow() {
            rowCount++;
            const tableBody = document.getElementById("dynamicTable").getElementsByTagName('tbody')[0];
            const newRow = tableBody.insertRow();

            // Create unique ID for the location input
            const itemInputId = `locations-${rowCount}`;

            newRow.innerHTML = `
                    <td>${rowCount}</td>
                    <td><input id="${itemInputId}" name="item_id[]" placeholder="Buscar Articulo..." class="form-control"></td>
                    <td><input type="email" name="email[]" class="form-control" placeholder="Enter email"></td>
                    <td><button type="button" class="btn btn-danger btn-sm" onclick="removeRow(this)">Remove</button></td>
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