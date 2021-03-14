<x-app-layout>
    <x-slot name="header">
        <x-title>{{ __('Create App') }}</x-title>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-md sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">
                    <form method="POST" action="{{ route('apps.store') }}">
                        @csrf

                        <!-- Name -->
                        <div>
                            <x-label for="name" :value="__('Name')" />
                            <x-input
                                id="name"
                                class="block mt-1 w-full"
                                type="text"
                                name="name"
                                :value="old('name')"
                                required
                                autofocus
                            />
                            <x-help-text>
                                {{ __('Please use only alpha-numeric characters, the hyphen symbol (-), and underscore (_).') }}
                            </x-help-text>
                            @error('name')
                                <x-invalid-field>{{ $message }}</x-invalid-field>
                            @enderror
                        </div>

                        <!-- AWS Client ID -->
                        <div>
                            <x-label for="name" :value="__('AWS Client ID')" />
                            <x-input
                                id="aws_client_id"
                                class="block mt-1 w-full"
                                type="text"
                                name="aws_client_id"
                                :value="old('aws_client_id')"
                                required
                            />
                            @error('aws_client_id')
                                <x-invalid-field>{{ $message }}</x-invalid-field>
                            @enderror
                        </div>

                        <!-- AWS Client Secret -->
                        <div>
                            <x-label for="name" :value="__('AWS Client Secret')" />
                            <x-input
                                id="aws_client_secret"
                                class="block mt-1 w-full"
                                type="text"
                                name="aws_client_secret"
                                :value="old('aws_client_secret')"
                                required
                            />
                            @error('aws_client_secret')
                                <x-invalid-field>{{ $message }}</x-invalid-field>
                            @enderror
                        </div>

                        <div class="flex items-center justify-end mt-4">
                            <x-button class="ml-3">
                                {{ __('Create') }}
                            </x-button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
