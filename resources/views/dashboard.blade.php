@extends('layouts.app')

@section('title', 'Dashboard')

@section('content')
    <div class="page">
        <div class="page-wrapper">
            <div class="page-header d-print-none">
                <div class="container-xl">
                    <div class="row g-2 align-items-center">
                        <div class="col-auto">
                            <span class="avatar avatar-md"
                                style="background-image: url({{ asset('img/' . Auth::user()->foto) }})"></span>
                        </div>
                        <div class="col">
                            <h2 class="page-title"> {{ $greeting }}, {{ Auth::user()->name }}
                                {{ Auth::user()->apellido }}!
                            </h2>
                            <div class="page-subtitle">
                                <div class="row">
                                    <div class="col-auto">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24"
                                            viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none"
                                            stroke-linecap="round" stroke-linejoin="round">
                                            <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                            <line x1="3" y1="21" x2="21" y2="21" />
                                            <path d="M5 21v-14l8 -4v18" />
                                            <path d="M19 21v-10l-6 -4" />
                                            <line x1="9" y1="9" x2="9" y2="9.01" />
                                            <line x1="9" y1="12" x2="9" y2="12.01" />
                                            <line x1="9" y1="15" x2="9" y2="15.01" />
                                            <line x1="9" y1="18" x2="9" y2="18.01" />
                                        </svg>
                                        {{ Auth::user()->puesto }}
                                    </div>
                                    <div class="col-auto text-blue">

                                        ¿Qué haremos hoy?
                                    </div>

                                </div>
                            </div>
                        </div>
                        <div class="col-auto d-none d-md-flex">
                            <a href="{{ route('profile.edit') }}" class="btn btn-primary">
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                                    fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                    stroke-linejoin="round" class="icon icon-tabler icons-tabler-outline icon-tabler-user">
                                    <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                    <path d="M8 7a4 4 0 1 0 8 0a4 4 0 0 0 -8 0" />
                                    <path d="M6 21v-2a4 4 0 0 1 4 -4h4a4 4 0 0 1 4 4v2" />
                                </svg>
                                Perfil
                            </a>
                        </div>
                    </div>
                </div>
            </div>

            <div class="page-body">
                <div class="container-xl">
                    <div class="row  row-cards">
                        <div class="col-md-4 col-12">
                            <div class="card">
                                <div class="card-status-start bg-orange"></div>
                                <div class="card-stamp">
                                    <div class="card-stamp-icon bg-orange">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                            viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                            stroke-linecap="round" stroke-linejoin="round"
                                            class="icon icon-tabler icons-tabler-outline icon-tabler-arrows-sort">
                                            <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                            <path d="M3 9l4 -4l4 4m-4 -4v14" />
                                            <path d="M21 15l-4 4l-4 -4m4 4v-14" />
                                        </svg>
                                    </div>
                                </div>
                                <div class="card-body d-flex justify-content-between align-items-center">
                                    <a href="{{ route('maxmin.index') }}" class="btn btn-ghost-orange">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                            viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                            stroke-linecap="round" stroke-linejoin="round"
                                            class="icon icon-tabler icons-tabler-outline icon-tabler-arrows-sort">
                                            <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                            <path d="M3 9l4 -4l4 4m-4 -4v14" />
                                            <path d="M21 15l-4 4l-4 -4m4 4v-14" />
                                        </svg>
                                        <h3 class="card-title mb-0">Máximos y mínimos</h3>
                                    </a>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-4 col-12">
                            <div class="card">
                                <div class="card-status-start bg-green"></div>
                                <div class="card-stamp">
                                    <div class="card-stamp-icon bg-green">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                            viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                            stroke-linecap="round" stroke-linejoin="round"
                                            class="icon icon-tabler icons-tabler-outline icon-tabler-box">
                                            <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                            <path d="M12 3l8 4.5l0 9l-8 4.5l-8 -4.5l0 -9l8 -4.5" />
                                            <path d="M12 12l8 -4.5" />
                                            <path d="M12 12l0 9" />
                                            <path d="M12 12l-8 -4.5" />
                                        </svg>
                                    </div>
                                </div>
                                <div class="card-body d-flex justify-content-between align-items-center">
                                    <a href="{{ route('cotizador-llantas.index') }}" class="btn btn-ghost-green">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                            viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                            stroke-linecap="round" stroke-linejoin="round"
                                            class="icon icon-tabler icons-tabler-outline icon-tabler-box">
                                            <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                            <path d="M12 3l8 4.5l0 9l-8 4.5l-8 -4.5l0 -9l8 -4.5" />
                                            <path d="M12 12l8 -4.5" />
                                            <path d="M12 12l0 9" />
                                            <path d="M12 12l-8 -4.5" />
                                        </svg>
                                        <h3 class="card-title mb-0">Cotizador</h3>
                                    </a>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-4 col-12">
                            <div class="card">
                                <div class="card-status-start bg-primary"></div>
                                <div class="card-stamp">
                                    <div class="card-stamp-icon bg-primary">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                            viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                            stroke-linecap="round" stroke-linejoin="round"
                                            class="icon icon-tabler icons-tabler-outline icon-tabler-brand-google-analytics">
                                            <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                            <path
                                                d="M10 9m0 1.105a1.105 1.105 0 0 1 1.105 -1.105h1.79a1.105 1.105 0 0 1 1.105 1.105v9.79a1.105 1.105 0 0 1 -1.105 1.105h-1.79a1.105 1.105 0 0 1 -1.105 -1.105z" />
                                            <path
                                                d="M17 3m0 1.105a1.105 1.105 0 0 1 1.105 -1.105h1.79a1.105 1.105 0 0 1 1.105 1.105v15.79a1.105 1.105 0 0 1 -1.105 1.105h-1.79a1.105 1.105 0 0 1 -1.105 -1.105z" />
                                            <path d="M5 19m-2 0a2 2 0 1 0 4 0a2 2 0 1 0 -4 0" />
                                        </svg>
                                    </div>
                                </div>
                                <div class="card-body d-flex justify-content-between align-items-center">
                                    <a href="{{ route('salesOrder.create') }}" class="btn btn-ghost-primary">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                            viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                            stroke-linecap="round" stroke-linejoin="round"
                                            class="icon icon-tabler icons-tabler-outline icon-tabler-brand-google-analytics">
                                            <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                            <path
                                                d="M10 9m0 1.105a1.105 1.105 0 0 1 1.105 -1.105h1.79a1.105 1.105 0 0 1 1.105 1.105v9.79a1.105 1.105 0 0 1 -1.105 1.105h-1.79a1.105 1.105 0 0 1 -1.105 -1.105z" />
                                            <path
                                                d="M17 3m0 1.105a1.105 1.105 0 0 1 1.105 -1.105h1.79a1.105 1.105 0 0 1 1.105 1.105v15.79a1.105 1.105 0 0 1 -1.105 1.105h-1.79a1.105 1.105 0 0 1 -1.105 -1.105z" />
                                            <path d="M5 19m-2 0a2 2 0 1 0 4 0a2 2 0 1 0 -4 0" />
                                        </svg>
                                        <h3 class="card-title mb-0">Órdenes de venta</h3>
                                    </a>
                                </div>
                            </div>
                        </div>


                    </div>
                </div>
            </div>
        </div>
    <div @endsection
