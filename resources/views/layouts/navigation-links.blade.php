<!-- Navigation Links -->

@php
function getOperationsBadgeCount() {
    return \App\Models\DispositionUnit::forOperator(getAuthenticatedUserModel()->id)->count();
}
@endphp

{{-- DISPOSITIONS --}}
@if(can('view_dispositions'))
    <x-nav-link-wrapper>
        <x-nav-link :href="route('dispositions.index')" :active="request()->routeIs('dispositions.index')">
            {{ __('Dispositions') }}
        </x-nav-link>
    </x-nav-link-wrapper>
@endif

{{-- OPERATIONS --}}
@if(can('view_operations'))
    <x-nav-link-wrapper>
        <x-nav-link :href="route('operations.index')" :active="request()->routeIs('operations.index')" :badge="getOperationsBadgeCount()">
            {{ __('Operations') }}
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

{{-- REGISTRATION REQUESTS FOR ADMIN --}}
@if (getAuthenticatedUserModel()->isAdmin())
    <x-nav-link-wrapper>
        <x-nav-link :href="route('registration-requests.index')" :active="request()->routeIs('registration-requests.index')">
            {{ __('Registration requests') }}
        </x-nav-link>
    </x-nav-link-wrapper>
@endif
