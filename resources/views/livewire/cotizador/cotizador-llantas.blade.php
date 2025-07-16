<div class="container py-4">
    <h1>Inventario cargado desde NetSuite</h1>
    <div class="row g-2 mb-3">
        <div class="col-md-2">
            <input wire:model.lazy="filtroMarca" type="text" class="form-control" placeholder="Marca">
        </div>
        <div class="col-md-2">
            <input wire:model.lazy="filtroAltura" type="text" class="form-control" placeholder="Altura">
        </div>
        <div class="col-md-2">
            <input wire:model.lazy="filtroAncho" type="text" class="form-control" placeholder="Ancho">
        </div>
        <div class="col-md-2">
            <input wire:model.lazy="filtroRin" type="text" class="form-control" placeholder="Rin">
        </div>
        <div class="col-md-2">
            <input wire:model.lazy="filtroPrecioMin" type="number" step="0.01" class="form-control"
                placeholder="Precio Min.">
        </div>
        <div class="col-md-2">
            <input wire:model.lazy="filtroPrecioMax" type="number" step="0.01" class="form-control"
                placeholder="Precio Max.">
        </div>
    </div>
    {{-- <pre>{{ json_encode($this->datosConsolidados->toArray(), JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE) }}</pre> --}}




    @php
        $ubicacionesFijas = [
            'MATRIZ',
            'VICENTE GUERRERO',
            'VALLEJO',
            'PONIENTE',
            'IXTAPALUCA',
            'QUERETARO',
            'GUADALAJARA',
            'OBREGON',
            'OBREGON A',
            'OBREGON B',
            'OBREGON MAYOREO',
            'JOJUTLA',
            'PLASTICOS',
            'JORGES',
            'MISAEL',
            'CHAMILPA',
            'VINILOS',
            'BDC',
            'BOD TEMPO 1',
            'BOD TEMPO 16',
        ];
        $nivelesPrecio = ['SEMI - MAYOREO', 'MAYOREO', 'PROMOCIÓN DEL MES', 'NK', 'PROMOCION POR PRONTO PAGO'];
    @endphp

    <div class="container">
        <div class="row">
            <div class="table-responsive">
                <table class="table table-card table-vcenter" id="tablaInventario">
                    <thead>
                        <tr>
                            <th>ARTÍCULO</th>
                            <th>DESCRIPCIÓN</th>
                            <th>APLICACIÓN</th>
                            <th>OE</th>
                            <th>MEDIDA EQUIVALENTE</th>
                            <th>MARCA</th>
                            <th>PROMOCIÓN</th>
                            {{-- Columnas de precios --}}
                            @foreach ($nivelesPrecio as $nivel)
                                <th>{{ $nivel }}</th>
                            @endforeach

                            {{-- Columnas de ubicaciones --}}
                            @foreach ($ubicacionesFijas as $ubicacion)
                                <th>{{ $ubicacion }}</th>
                            @endforeach
                        </tr>
                    </thead>
                    <tbody>

                        {{-- Iterar sobre los artículos del inventario --}}
                        @foreach ($this->datosConsolidados as $item)
                            <tr>
                                <td>{{ $item['itemid'] }}</td>
                                <td>{{ $item['descripcion'] }}</td>
                                <td>{{ $item['aplicacion'] }}</td>
                                <td>{{ $item['oe'] }}</td>
                                <td>{{ $item['medida_equivalente'] }}</td>
                                <td>{{ $item['marca'] }}</td>
                                <td>{{ $item['promocion'] }}</td>

                                {{-- Valores de precio --}}
                                @foreach ($nivelesPrecio as $nivel)
                                    @php
                                        $precio =
                                            collect($item['precios'])->firstWhere('nivel', $nivel)['precio'] ?? '-';
                                    @endphp
                                    <td>{{ number_format((float) $precio, 2) }}</td>
                                @endforeach

                                {{-- Valores de stock por ubicación --}}
                                @foreach ($ubicacionesFijas as $ubi)
                                    @php
                                        $cantidad =
                                            collect($item['ubicaciones'])->firstWhere('ubicacion', $ubi)[
                                                'disponible'
                                            ] ?? 0;
                                    @endphp
                                    <td>{{ $cantidad }}</td>
                                @endforeach
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

    </div>
</div>
