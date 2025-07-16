@extends('layouts.app')

@section('title', 'Dashboard')

@section('content')

    @livewire('cotizador.cotizador-llantas')

@endsection

@section('scripts')
    <script>
        $(document).ready(function() {
            $('#tablaInventario').DataTable({
                responsive: true,
                pageLength: 10,
                order: [
                    [0, 'asc']
                ],
                language: {
                    url: "https://cdn.datatables.net/plug-ins/1.13.5/i18n/es-MX.json",
                    "oPaginate": {
                        "sFirst": "«",
                        "sLast": "»",
                        "sNext": "Sig.",
                        "sPrevious": "Ant."
                    }
                },
            });
        });
    </script>

@endsection
