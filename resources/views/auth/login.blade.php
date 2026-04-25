<x-guest-layout>
    <!-- Session Status -->
    <x-auth-session-status class="mb-4" :status="session('status')" />

    <form method="POST" action="{{ route('login') }}">
        @csrf

        <!-- Email Address -->
        <div>
            <x-input-label for="email" :value="__('Email')" />
            <x-text-input id="email" class="block mt-1 w-full" type="email" name="email" :value="old('email')" required autofocus autocomplete="username" />
            <x-input-error :messages="$errors->get('email')" class="mt-2" />
        </div>

        <!-- Password -->
        <div class="mt-4">
            <x-input-label for="password" :value="__('Password')" />

            <x-text-input id="password" class="block mt-1 w-full"
                            type="password"
                            name="password"
                            required autocomplete="current-password" />

            <x-input-error :messages="$errors->get('password')" class="mt-2" />
        </div>

        <div class="flex items-center justify-between mt-6">
            <div class="remember-me">
                <label for="remember_me" class="inline-flex items-center text-sm text-gray-600 cursor-pointer">
                    <input id="remember_me" type="checkbox" class="rounded border-gray-300 text-adminlte-primary shadow-sm focus:ring-adminlte-primary" name="remember">
                    <span class="ms-2">{{ __('Remember me') }}</span>
                </label>
            </div>

            <button type="submit" class="bg-adminlte-primary hover:bg-blue-700 text-white font-bold py-2 px-6 rounded focus:outline-none focus:shadow-outline transition-colors">
                {{ __('Log in') }}
            </button>
        </div>

        @if (Route::has('password.request'))
            <div class="text-center mt-4">
                <a class="text-sm text-adminlte-primary hover:underline" href="{{ route('password.request') }}">
                    {{ __('Forgot your password?') }}
                </a>
            </div>
        @endif
    </form>
</x-guest-layout>
