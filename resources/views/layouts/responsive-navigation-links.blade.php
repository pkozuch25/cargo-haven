<!-- Navigation Links -->

{{-- DISPOSITIONS --}}
@if(can('view_dispositions'))
    <x-responsive-nav-link :href="route('dispositions.index')" :active="request()->routeIs('dispositions.index')">
        {{ __('Dispositions') }}
    </x-responsive-nav-link>
@endif

{{-- OPERATIONS --}}
@if(can('view_operations'))
    <x-responsive-nav-link :href="route('operations.index')" :active="request()->routeIs('operations.index')" :badge="getOperationsBadgeCount()">
        {{ __('Operations') }}
    </x-responsive-nav-link>
@endif

{{-- DEPOSITS --}}
@if(can('view_deposits'))
    <x-responsive-nav-link :href="route('deposits.index')" :active="request()->routeIs('deposits.index')">
        {{ __('Deposits') }}
    </x-responsive-nav-link>
@endif

{{-- STORAGE YARDS --}}
@if(can('view_storage_yards'))
    <x-responsive-nav-link :href="route('storage-yards.index')" :active="request()->routeIs('storage-yards.index')">
        {{ __('Storage yards') }}
    </x-responsive-nav-link>
@endif

{{-- REGISTRATION REQUESTS FOR ADMIN --}}
@if (getAuthenticatedUserModel()->isAdmin())
    <x-responsive-nav-link :href="route('registration-requests.index')" :active="request()->routeIs('registration-requests.index')">
        {{ __('Registration requests') }}
    </x-responsive-nav-link>
@endif
