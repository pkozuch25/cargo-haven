<x-guest-layout>
    <div class="w-full sm:max-w-md mt-6 px-6 py-4 dark:bg-gray-800 shadow-md overflow-hidden sm:rounded-lg">
        <!-- Session Status -->
        <x-auth-session-status class="mb-4" :status="session('status')" />

        <form method="POST" action="{{ route('login') }}">
            @csrf

            <!-- Email Address -->
            <div>
                <x-input-label for="email" :value="__('Email')" />
                <x-input id="email" class="block mt-1 w-full" type="email" name="email" :value="old('email')" required autofocus autocomplete="username" />
                <x-input-error :messages="$errors->get('email')" class="mt-2" />
            </div>

            <!-- Password -->
            <div class="mt-4">
                <x-input-label for="password" :value="__('Hasło')" />

                <x-input id="password" class="block mt-1 w-full"
                                type="password"
                                name="password"
                                required autocomplete="current-password" />

                <x-input-error :messages="$errors->get('password')" class="mt-2" />
            </div>

            <!-- Remember Me -->
            <div class="block mt-4">
                <label for="remember_me" class="inline-flex items-center">
                    <input id="remember_me" type="checkbox" class="rounded dark:bg-gray-900 border-gray-300 dark:border-gray-700 text-lime-600 shadow-sm ring-lime-500 dark:focus:ring-lime-600 dark:focus:ring-offset-gray-800" name="remember">
                    <span class="ms-2 text-sm text-gray-600 dark:text-gray-400">{{ __('Zapamiętaj mnie') }}</span>
                </label>
            </div>

            <div class="flex items-center justify-end mt-4">
                @if (Route::has('password.request'))
                    <a class="underline text-sm text-gray-600 dark:text-gray-400 hover:text-gray-900 dark:hover:text-gray-100 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-lime-500 dark:focus:ring-offset-gray-800" href="{{ route('password.request') }}">
                        {{ __('Zapomniałeś hasła?') }}
                    </a>
                @endif

                <x-primary-button class="ms-3">
                    {{ __('Zaloguj') }}
                </x-primary-button>
            </div>
        </form>
        @php
        if(!isset($displayMessage)) {
            $displayMessage = 0;
        }
        @endphp
    </div>
    @if (Route::has('register'))
        <x-hover-underline-link-text style="margin-top: 12px" route="{{ route('register') }}">
            {{ __('Nie masz konta? Zarejestruj się') }}
        </x-hover-underline-link-text>
    @endif

    @push('javascript')
        <script>
            $(function() {
                console.log('{{ $displayMessage }}');

                var displayMessage = {{ $displayMessage }}

                if(displayMessage == 1) {
                    Swal.fire({
                        position: 'top-end',
                        background: '#1f2937',
                        icon: 'success',
                        color: '#ffffff',
                        title: '{{ __("Success") }}',
                        html: '{{ __("Registration request was successfully submitted to administrator, you will be notified via email with the decision.")}}',
                        showConfirmButton: false,
                        timer: 3500,
                        customClass: {
                        confirmButton: 'btn btn-primary',
                        },
                        buttonsStyling: false
                    });
                }

            })
        </script>
    @endpush
</x-guest-layout>
