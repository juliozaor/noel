
<x-guest-layout>
    <div class="container-login">
        <div class="login-container">
            <div class="login-image"></div>
            <div class="login-form ">
                <span class="titulo">Actualice su contraseña</span>
                <span class="sub-titulo">Ingresa tus datos para actualizar</span>
                <form id="updatePass" method="POST" action="{{ route('auth.update.pass') }}">
                    @csrf

                    <input type="hidden" name="token" value="{{ $token }}">

                    <div class="block">
                        {{--  <x-input id="email" class="block mt-1 w-full" type="hidden" name="email"
                            :value="$request->query('email')" required autofocus autocomplete="username" /> --}}
                        <x-input id="email" class="block mt-1 w-full" type="hidden" name="email"
                            :value="request()->query('email')" required autofocus autocomplete="username" />
                    </div>

                    <div class="mt-4">
                        <x-label for="password" value="{{ __('Contraseña') }}" />
                        <x-input id="password" class="block mt-1 w-full" type="password" name="password" required
                            autocomplete="new-password" />
                    </div>

                    <div class="mt-4">
                        <x-label for="password_confirmation" value="{{ __('Confirmar contraseña') }}" />
                        <x-input id="password_confirmation" class="block mt-1 w-full" type="password"
                            name="password_confirmation" required autocomplete="new-password" />
                    </div>

                    
                    
                    <div class="flex items-center justify-end mt-4">
                        <x-button>
                            {{ __('Actualizar contraseña') }}
                        </x-button>
                    </div>
                </form>
                
                <span id="errores-container" class="sub-titulo mt-4"></span>

            </div>
        </div>
    </div>

    <script>

document.getElementById('updatePass').addEventListener('submit', function(event) {
    event.preventDefault(); // Evita la recarga de la página

const formData = new FormData(event.target);
fetch('/auth/update', {
    method: 'POST',
    body: formData
})
.then(response => response.json())
.then(data => {
    if (data.success) {
            console.log(data.message);
            window.location.href = '{{ route('login') }}';
        } else {
            
            const errorsContainer = document.getElementById('errores-container');
            errorsContainer.innerHTML = ''; // Limpia los errores anteriores

            for (const fieldName in data.errors) {
                const errorMessage = data.errors[fieldName].join('<br>');
                /* const errorElement = document.createElement('div');
                errorElement.innerHTML = `<strong>Error en ${fieldName}:</strong><br>${errorMessage}`;
                console.log(errorElement);
                errorsContainer.appendChild(errorElement); */
                errorsContainer.innerHTML = errorMessage;
            }
        }
});
});
    </script>

</x-guest-layout>
