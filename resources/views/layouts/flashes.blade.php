@if ($errors->any())
    <x-alerts.danger>
        There were some problems with the value you submitted.
    </x-alerts.danger>
@endif

@if (session('status'))
    <x-alerts.success :value="session('status')" />
@endif

@if (session('resource_destroy_success'))
    <x-alerts.success>
        {{ session('resource_name', 'Resource') }} removed successfully.
    </x-alerts.success>
@endif

@if (session('resource_store_success'))
    <x-alerts.success>
        {{ session('resource_name', 'Resource') }} stored successfully.
    </x-alerts.success>
@endif

@if (session('resource_update_success'))
    <x-alerts.success>
        {{ session('resource_name', 'Resource') }} updated successfully.
    </x-alerts.success>
@endif
