
<!-- Navigation Links -->

{{-- DASHBOARD --}}
<x-nav-link-wrapper>
    <x-nav-link :href="route('dashboard')" :active="request()->routeIs('dashboard')">
        {{ __('Dashboard') }}
    </x-nav-link>
</x-nav-link-wrapper>

{{-- DEPOSITS --}}  
<x-nav-link-wrapper>
    <x-nav-link :href="route('deposits.index')" :active="request()->routeIs('deposits.index')">
        {{ __('Deposits') }}
    </x-nav-link>
</x-nav-link-wrapper>

{{-- PERMISSIONS --}}
<x-nav-link-wrapper>
    <x-nav-link :href="route('permissions.index')" :active="request()->routeIs('permissions.index')">
        {{ __('Permissions') }}
    </x-nav-link>
</x-nav-link-wrapper>