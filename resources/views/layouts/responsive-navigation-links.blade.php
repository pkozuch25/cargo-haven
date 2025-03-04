<!-- Navigation Links -->

{{-- DASHBOARD --}}
<x-responsive-nav-link :href="route('dashboard')" :active="request()->routeIs('dashboard')">
    {{ __('Dashboard') }}
</x-responsive-nav-link>

{{-- DEPOSITS --}}
@if(can('view_deposits'))
    <x-responsive-nav-link :href="route('deposits.index')" :active="request()->routeIs('deposits.index')">
        {{ __('Deposits') }}
    </x-responsive-nav-link>
@endif

{{-- DISPOSITIONS --}}
@if(can('view_dispositions'))
    <x-responsive-nav-link :href="route('dispositions.index')" :active="request()->routeIs('dispositions.index')">
        {{ __('Dispositions') }}
    </x-responsive-nav-link>
@endif

{{-- PERMISSIONS --}}
@if(can('manage_permissions'))
    <x-responsive-nav-link :href="route('permissions.index')" :active="request()->routeIs('permissions.index')">
        {{ __('Permissions') }}
    </x-responsive-nav-link>
@endif

{{-- REGISTRATION REQUESTS FOR ADMIN --}}
@if (getAuthenticatedUserModel()->isAdmin())
    <x-responsive-nav-link :href="route('registration-requests.index')" :active="request()->routeIs('registration-requests.index')">
        {{ __('Registration requests') }}
    </x-responsive-nav-link>
@endif