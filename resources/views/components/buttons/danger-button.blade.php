<button type="{{ $type }}" {{ $attributes->merge([
    'class' => 'inline-flex items-center px-4 py-2 border border-red-700 rounded-md font-semibold text-xs text-red-700 uppercase tracking-widest disabled:opacity-25 transition ease-in-out duration-150',
]) }}>
    {{ $slot }}
</button>
