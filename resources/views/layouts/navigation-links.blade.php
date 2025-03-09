
<!-- Navigation Links -->

{{-- DASHBOARD --}}
<x-nav-link-wrapper>
    <x-nav-link :href="route('dashboard')" :active="request()->routeIs('dashboard')">
        {{ __('Dashboard') }}
    </x-nav-link>
</x-nav-link-wrapper>

{{-- DISPOSITIONS --}}
@if(can('view_dispositions'))
    <x-nav-link-wrapper>
        <x-nav-link :href="route('dispositions.index')" :active="request()->routeIs('dispositions.index')">
            {{ __('Dispositions') }}
        </x-nav-link>
    </x-nav-link-wrapper>
@endif

{{-- DEPOSITS --}}
@if(can('view_deposits'))
    <x-nav-link-wrapper>
        <x-nav-link :href="route('deposits.index')" :active="request()->routeIs('deposits.index')">
            {{ __('Deposits') }}
        </x-nav-link>
    </x-nav-link-wrapper>
@endif

{{-- STORAGE YARDS --}}
@if(can('view_storage_yards'))
    <x-nav-link-wrapper>
        <x-nav-link :href="route('storage-yards.index')" :active="request()->routeIs('storage-yards.index')">
            {{ __('Storage yards') }}
        </x-nav-link>
    </x-nav-link-wrapper>
@endif

{{-- DEPOSITS --}}
@if(can('view_deposits'))
    <x-nav-link-wrapper>
        <x-nav-link :href="route('deposits.index')" :active="request()->routeIs('deposits.index')">
            {{ __('Deposits') }}
        </x-nav-link>
    </x-nav-link-wrapper>
@endif

{{-- PERMISSIONS --}}
@if(can('manage_permissions'))
    <x-nav-link-wrapper>
        <x-nav-link :href="route('permissions.index')" :active="request()->routeIs('permissions.index')">
            {{ __('Permissions') }}
        </x-nav-link>
    </x-nav-link-wrapper>
@endif

{{-- REGISTRATION REQUESTS FOR ADMIN --}}
@if (getAuthenticatedUserModel()->isAdmin())
    <x-nav-link-wrapper>
        <x-nav-link :href="route('registration-requests.index')" :active="request()->routeIs('registration-requests.index')">
            {{ __('Registration requests') }}
        </x-nav-link>
    </x-nav-link-wrapper>
@endif
