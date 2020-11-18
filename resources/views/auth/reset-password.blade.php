<x-guest-layout>
    <x-auth-card>
        <x-slot name="logo">
            <a href="/">
                <x-application-logo class="w-20 h-20 fill-current text-gray-500" />
            </a>
        </x-slot>

        <form method="POST" action="{{ route('auth.password.update') }}">
            @csrf

            <!-- Password Reset Token -->
            <input type="hidden" name="token" value="{{ $request->route('token') }}">

            <!-- Email Address -->
            @if (request('email'))
                <input type="hidden" name="email" value="{{ request('email') }}">
            @else
                <div>
                    <x-label for="email" :value="__('Email')" />
                    <x-input
                        id="email"
                        class="block mt-1 w-full"
                        type="email"
                        name="email"
                        required
                        autofocus
                    />
                </div>
            @endif

            <!-- Password -->
            <div class="mt-4">
                <label for="password" class="block font-medium text-sm text-gray-700">
                    {{ __('Password') }}
                </label>
                <x-input
                    id="password"
                    class="block mt-1 w-full"
                    type="password"
                    name="password"
                    required
                />
            </div>

            <!-- Confirm Password -->
            <div class="mt-4">
                <label for="password_confirmation" class="block font-medium text-sm text-gray-700">
                    {{ __('Confirm Password') }}
                </label>

                <x-input
                    id="password_confirmation"
                    class="block mt-1 w-full"
                    type="password"
                    name="password_confirmation"
                    required
                />
            </div>

            <div class="flex items-center justify-end mt-4">
                <a class="underline text-sm text-gray-600 hover:text-gray-900" href="{{ route('auth.login') }}">
                    {{ __('Back to login') }}
                </a>

                <x-button class="ml-3">
                    {{ __('Reset Password') }}
                </x-button>
            </div>
        </form>
    </x-auth-card>
</x-guest-layout>
