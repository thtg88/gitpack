<x-app-layout>
    <x-slot name="header">
        <div class="flex-1 min-w-0">
            <div class="flex items-center">
                <x-title>{{ __('Edit App') }}</x-title>
            </div>
        </div>
        <div class="mt-4 flex-shrink-0 flex md:mt-0 md:ml-4">
            <form action="{{ route('apps.destroy', $resource) }}" method="post">
                @csrf
                @method('delete')
                <x-buttons.danger-button type="submit" data-confirm="Are you sure you want to remove this app?">
                    Remove app
                </x-buttons.danger-button>
            </form>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-md sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">
                    <p>Config Variables</p>

                    @foreach ($resource->environment_variables as $_environment_variable)
                        <span class="block">
                            {{ $_environment_variable->name }}
                            {{ $_environment_variable->value }}
                            <form
                                action="{{ route('environment-variables.destroy', $_environment_variable) }}"
                                method="post"
                                class="inline"
                            >
                                @csrf
                                @method('delete')
                                <x-buttons.danger-button
                                    type="submit"
                                    data-confirm="Are you sure you want to remove this environment variable?"
                                >
                                    Remove
                                </x-buttons.danger-button>
                            </form>
                        </span>
                    @endforeach

                    <form method="post" action="{{ route('environment-variables.store') }}">
                        @csrf

                        <input type="hidden" name="app_id" value="{{ $resource->id }}">

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
                        <div>
                            <x-label for="value" :value="__('Value')" />
                            <x-input
                                id="value"
                                class="block mt-1 w-full"
                                type="text"
                                name="value"
                                :value="old('value')"
                            />
                            <x-help-text>
                                {{ __('Max length 65536 characters') }}
                            </x-help-text>
                            @error('value')
                                <x-invalid-field>{{ $message }}</x-invalid-field>
                            @enderror
                        </div>

                        <div class="flex items-center justify-end mt-4">
                            <x-button class="ml-3">
                                {{ __('Add') }}
                            </x-button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-md sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">
                    <form method="post" action="{{ route('apps.update', $resource) }}">
                        @csrf
                        @method('put')

                        <!-- Name -->
                        <div>
                            <x-label for="name" :value="__('Name')" />
                            <x-input
                                id="name"
                                class="block mt-1 w-full"
                                type="text"
                                name="name"
                                :value="old('name', $resource->name)"
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

                        <div class="flex items-center justify-end mt-4">
                            <x-button class="ml-3">
                                {{ __('Update') }}
                            </x-button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
