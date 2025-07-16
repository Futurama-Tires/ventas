<section>
    <div class="container">
        <header>
            <h2 class="text-lg font-medium text-gray-900">
                {{ __('Actualizar Contraseña') }}
            </h2>

            <p class="mt-1 text-sm text-gray-600">
                {{ __('Asegúrese de que su cuenta utilice una contraseña larga y aleatoria para mantenerse segura.') }}
            </p>
        </header>

        <form method="post" action="{{ route('password.update') }}" class="mt-6 space-y-6">
            @csrf
            @method('put')

            <div class="mb-3">
                <label class="form-label" for="update_password_current_password">Contraseña Actual</label>
                <input type="password" id="update_password_current_password" class="form-control" name="current_password"
                    placeholder="Contraseña Actual" />

                @if ($errors->updatePassword->has('current_password'))
                    <div class="alert alert-danger d-flex align-items-center mt-2" role="alert" id="danger-alert">
                        <div>
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                                fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                stroke-linejoin="round" class="icon icon-tabler icons-tabler-outline icon-tabler-x">
                                <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                <path d="M18 6l-12 12" />
                                <path d="M6 6l12 12" />
                            </svg>
                        </div>
                        <div class="ms-2">
                            <h4 class="alert-title">{{ __('¡Ocurrió un error!') }}</h4>
                            <div class="text-secondary">{{ $errors->updatePassword->first('current_password') }}</div>
                        </div>
                    </div>
                @endif
            </div>

            <div class="mb-3">
                <label class="form-label" for="update_password_password">Nueva Contraseña</label>
                <input type="password" id="update_password_password" class="form-control" name="password"
                    placeholder="Nueva Contraseña" />
                @if ($errors->updatePassword->has('password'))
                    <div class="alert alert-danger d-flex align-items-center mt-2" role="alert" id="danger-alert">
                        <div>
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                                fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                stroke-linejoin="round" class="icon icon-tabler icons-tabler-outline icon-tabler-x">
                                <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                <path d="M18 6l-12 12" />
                                <path d="M6 6l12 12" />
                            </svg>
                        </div>
                        <div class="ms-2">
                            <h4 class="alert-title">{{ __('¡Ocurrió un error!') }}</h4>
                            <div class="text-secondary">{{ $errors->updatePassword->first('password') }}</div>
                        </div>
                    </div>
                @endif
            </div>

            <div class="mb-3">
                <label class="form-label" for="update_password_password_confirmation">Confirmar Contraseña</label>
                <input type="password" id="update_password_password_confirmation" class="form-control"
                    name="password_confirmation" placeholder="Confirmar Contraseña" />

                @if ($errors->updatePassword->has('password_confirmation'))
                    <div class="alert alert-danger d-flex align-items-center mt-2" role="alert" id="danger-alert">
                        <div>
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                                fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                stroke-linejoin="round" class="icon icon-tabler icons-tabler-outline icon-tabler-x">
                                <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                <path d="M18 6l-12 12" />
                                <path d="M6 6l12 12" />
                            </svg>
                        </div>
                        <div class="ms-2">
                            <h4 class="alert-title">{{ __('¡Ocurrió un error!') }}</h4>
                            <div class="text-secondary">{{ $errors->updatePassword->first('password_confirmation') }}
                            </div>
                        </div>
                    </div>
                @endif
            </div>

            <button class="btn btn-green my-4">{{ __('Guardar') }}</button>

            <!-- Mensaje de éxito -->
            @if (session('status') === 'password-updated')
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
                        <div class="text-secondary">{{ __('Tu contraseña ha sido actualizada.') }}</div>
                    </div>
                </div>
            @endif
        </form>
    </div>
</section>
