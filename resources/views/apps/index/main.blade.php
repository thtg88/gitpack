<x-app-layout>
    <x-slot name="header">
        <div class="flex-1 min-w-0">
            <div class="flex items-center">
                <x-title>{{ __('Apps') }}</x-title>
            </div>
        </div>
        <div class="mt-4 flex-shrink-0 flex md:mt-0 md:ml-4">
            <x-button-link :href="route('apps.create')">Create app</x-button-link>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-md sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">
                    @foreach ($resources as $resource)
                        <div class="mb-6">
                            <a href="{{ route('apps.edit', $resource) }}">{{ $resource->name }}</a>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
