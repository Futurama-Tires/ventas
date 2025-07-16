<section>
    <div class="container">
        <header>
            <h2 class="text-lg font-medium text-gray-900">
                {{ __('Información de Perfil') }}
            </h2>

            <p class="mt-1 text-sm text-gray-600">
                {{ __('Actualice la información del perfil y la dirección de correo electrónico de su cuenta.') }}
            </p>
        </header>

        <form method="post" action="{{ route('profile.update') }}" class="mt-6 space-y-6">
            @csrf
            @method('patch')

            <div class="row align-items-center">
                <div class="col-auto"><span class="avatar avatar-xl {{ session('avatar_class', 'bg-primary-lt') }}">
                        {{ substr(Auth::user()->name, 0, 1) }}{{ substr(Auth::user()->apellido, 0, 1) }}
                    </span>
                </div>
                {{-- <div class="col-auto"><a href="#" class="btn disabled">
                        Cambiar foto de perfil
                    </a></div> --}}
            </div>

            <div class="row g-3 mt-2">
                <div class="col-md">
                    <label for="name" class="form-label">Nombre</label>
                    <input type="text" id="name" name="name" class="form-control"
                        value="{{ old('name', $user->name) }}" autocomplete="name" required>
                </div>
                <div class="col-md">
                    <label for="apellido" class="form-label">Apellido</label>
                    <input type="text" id="apellido" name="apellido" class="form-control"
                        value="{{ old('apellido', $user->apellido) }}" autocomplete="apellido" required>
                </div>
                <div class="col-md">
                    <label for="puesto" class="form-label">Puesto</label>
                    <input type="text" id="puesto" name="puesto" class="form-control"
                        value="{{ old('puesto', $user->puesto) }}" autocomplete="apellido" required>
                </div>
                <div class="col-md">
                    <label for="email" class="form-label">Correo electrónico</label>
                    <input type="text" id="email" name="email" class="form-control"
                        value="{{ old('email', $user->email) }}" autocomplete="email" required>
                </div>
            </div>

            <button class="btn btn-green my-4">{{ __('Guardar') }}</button>

            <!-- Mensaje de éxito -->
            @if (session('status') === 'profile-updated')
                <div class="alert alert-success d-flex align-items-center" role="alert" id="alert">
                    <div>
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                            fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                            stroke-linejoin="round" class="icon icon-tabler icon-tabler-check">
                            <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                            <path d="M5 12l5 5l10 -10" />
                        </svg>
                    </div>
                    <div class="ms-2">
                        <h4 class="alert-title">{{ __('Guardado exitosamente') }}</h4>
                        <div class="text-secondary">{{ __('Tu cuenta ha sido actualizada.') }}</div>
                    </div>
                </div>
            @endif
        </form>
    </div>
</section>
