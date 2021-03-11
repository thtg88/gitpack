<x-app-layout>
    <x-slot name="header">
        <div class="flex-1 min-w-0">
            <div class="flex items-center">
                <x-title>{{ __('SSH Keys') }}</x-title>
            </div>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-md sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">
                    <p>
                        @if (auth()->user()->ssh_keys_last_generated_at !== null)
                            {{ __('You have generated your SSH keys last on: ') }}
                            <strong>
                                {{ auth()->user()->ssh_keys_last_generated_at->format('d/m/y') }}
                                {{ __('at') }}
                                {{ auth()->user()->ssh_keys_last_generated_at->format('H:i') }}
                            </strong>
                        @else
                            {{ __('You have not generated your SSH keys yet.') }}
                        @endif
                    </p>
                    <p>
                        Please note we do not store these keys,
                        so store them in a safe place,
                        or we will have to re-generate them for you if you lose them.
                        All previous accesses of those keys will be revoked.
                    </p>
                    <form action="{{ route('ssh-keys.store') }}" method="post">
                        @csrf
                        <p>A .zip file will be downloaded with your public and private key pair.</p>
                        <x-button type="submit">
                            @if (auth()->user()->ssh_keys_last_generated_at !== null)
                                {{ __('Re-generate keys') }}
                            @else
                                {{ __('Generate keys') }}
                            @endif
                        </x-button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
