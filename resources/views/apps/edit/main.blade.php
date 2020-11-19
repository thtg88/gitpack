<x-app-layout>
    <x-slot name="header">
        <x-title>{{ __('Edit App') }}</x-title>
    </x-slot>

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
