<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Inicio de sesión</title>
    <link rel="icon" href="{{ asset('img/faviconfuturama.png') }}" type="image/png" />
    <script src="https://cdn.jsdelivr.net/npm/@tabler/core@1.0.0-beta17/dist/js/tabler.min.js"></script>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@tabler/core@1.0.0-beta17/dist/css/tabler.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@tabler/core@1.0.0-beta17/dist/css/tabler-flags.min.css">
    <link rel="stylesheet"
        href="https://cdn.jsdelivr.net/npm/@tabler/core@1.0.0-beta17/dist/css/tabler-payments.min.css">
    <link rel="stylesheet"
        href="https://cdn.jsdelivr.net/npm/@tabler/core@1.0.0-beta17/dist/css/tabler-vendors.min.css">

    <style>
        body {
            overflow: hidden;
        }
    </style>
</head>

<body class="bg-white">
    <div class="row g-0 flex-fill">
        <div class="col-12 col-lg-6 col-xl-4 border-top-wide border-primary d-flex flex-column justify-content-center">
            <div class="container container-tight my-5 px-lg-4">
                <div class="text-center mb-4">
                    <a href="." class="navbar-brand navbar-brand-autodark"><img
                            src="{{ asset('img/futurama_logo2.png') }}" height="36" alt=""></a>
                </div>
                <h2 class="h3 text-center mb-3">
                    Accede a tu cuenta
                </h2>
                <form action="{{ route('login') }}" method="POST" novalidate>
                    @csrf
                    <div class="mb-3">
                        <label class="form-label">Correo electrónico</label>
                        <input type="email" value="{{ old('email') }}" class="form-control"
                            placeholder="ejemplo@email.com" name="email" required>
                        <div class="invalid-feedback">Escribe un correo válido</div>
                    </div>
                    <div class="mb-2">
                        <label class="form-label">
                            Contraseña
                            {{-- <span class="form-label-description">
                                <a href="./forgot-password.html">Olvidé mi contraseña</a>
                            </span> --}}
                        </label>
                        <div class="input-group input-group-flat">
                            <input type="password" id="password" name="password" class="form-control"
                                placeholder="Tu contraseña" autocomplete="off" required>
                            <span class="input-group-text" onclick="togglePassword()">
                                <svg id="password-icon" xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                    viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                    stroke-linecap="round" stroke-linejoin="round" class="icon">
                                    <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                    <path d="M10 12a2 2 0 1 0 4 0a2 2 0 0 0 -4 0" />
                                    <path
                                        d="M21 12c-2.4 4 -5.4 6 -9 6c-3.6 0 -6.6 -2 -9 -6c2.4 -4 5.4 -6 9 -6c3.6 0 6.6 2 9 6" />
                                </svg>
                            </span>
                        </div>
                    </div>
                    <div class="mb-2">
                        {{-- <label class="form-check">
                            <input type="checkbox" class="form-check-input" />
                            <span class="form-check-label">Recordarme en este dispositivo</span>
                        </label> --}}
                    </div>
                    <div class="form-footer">
                        <button type="submit" class="btn btn-primary w-100">Iniciar sesión</button>
                    </div>
                    @if ($errors->has('email') || $errors->has('password'))
                        <div class="alert alert-danger m-0 mt-4">
                            <a href="#" class="alert-link">¡Correo o contraseña incorrectos!</a>
                        </div>
                    @endif
                </form>
                {{-- <div class="text-center text-secondary mt-3">
                    Don't have account yet? <a href="./sign-up.html" tabindex="-1">Sign up</a>
                </div> --}}
            </div>
        </div>
        <div class="col-12 col-lg-6 col-xl-8 d-none d-lg-block">
            <div id="carousel-indicators-thumb" class="carousel slide carousel-fade bg-cover vh-100"
                data-bs-ride="carousel">
                <!-- Indicadores -->
                <div class="carousel-indicators carousel-indicators-thumb">
                    <button type="button" data-bs-target="#carousel-indicators-thumb" data-bs-slide-to="0"
                        class="ratio ratio-4x3 active"
                        style="background-image: url({{ asset('img/background7.jpg') }})"></button>
                    <button type="button" data-bs-target="#carousel-indicators-thumb" data-bs-slide-to="1"
                        class="ratio ratio-4x3"
                        style="background-image: url({{ asset('img/background4.jpg') }})"></button>
                    <button type="button" data-bs-target="#carousel-indicators-thumb" data-bs-slide-to="2"
                        class="ratio ratio-4x3"
                        style="background-image: url({{ asset('img/background6.jpg') }})"></button>
                    <button type="button" data-bs-target="#carousel-indicators-thumb" data-bs-slide-to="3"
                        class="ratio ratio-4x3"
                        style="background-image: url({{ asset('img/background8.jpg') }})"></button>
                    <button type="button" data-bs-target="#carousel-indicators-thumb" data-bs-slide-to="4"
                        class="ratio ratio-4x3"
                        style="background-image: url({{ asset('img/background3.jpg') }})"></button>
                </div>
                <!-- Contenido -->
                <div class="carousel-inner">
                    <div class="carousel-item active">
                        <img class="d-block w-100 vh-100 object-cover" alt="Group of People Sightseeing in the City"
                            src="{{ asset('img/background7.jpg') }}" />
                    </div>
                    <div class="carousel-item">
                        <img class="d-block w-100 vh-100 object-cover" alt="Young Woman Working in a Cafe"
                            src="{{ asset('img/background4.jpg') }}" />
                    </div>
                    <div class="carousel-item">
                        <img class="d-block w-100 vh-100 object-cover"
                            alt="Soft Photo of Woman on the Bed With the Book and Cup of Coffee"
                            src="{{ asset('img/background6.jpg') }}" />
                    </div>
                    <div class="carousel-item">
                        <img class="d-block w-100 vh-100 object-cover" alt="Stylish Workplace With Computer at Home"
                            src="{{ asset('img/background8.jpg') }}" />
                    </div>
                    <div class="carousel-item">
                        <img class="d-block w-100 vh-100 object-cover" alt="Stylish Workspace with Mackbook Pro"
                            src="{{ asset('img/background3.jpg') }}" />
                    </div>
                </div>
            </div>

        </div>
    </div>

    <script>
        function togglePassword() {
            const passwordField = document.getElementById('password');
            const passwordIcon = document.getElementById('password-icon');

            if (passwordField.type === 'password') {
                passwordField.type = 'text';
                passwordIcon.innerHTML = `
                    <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                    <path d="M10 12a2 2 0 1 0 4 0a2 2 0 0 0 -4 0" />
                    <path d="M3 3l18 18" />
                    <path d="M21 12c-2.4 4 -5.4 6 -9 6c-3.6 0 -6.6 -2 -9 -6" />
                `; // Icono "ocultar"
            } else {
                passwordField.type = 'password';
                passwordIcon.innerHTML = `
                    <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                    <path d="M10 12a2 2 0 1 0 4 0a2 2 0 0 0 -4 0" />
                    <path d="M21 12c-2.4 4 -5.4 6 -9 6c-3.6 0 -6.6 -2 -9 -6c2.4 -4 5.4 -6 9 -6c3.6 0 6.6 2 9 6" />
                `; // Icono "mostrar"
            }
        }
    </script>

</body>

</html>
