
@if (!$responsive)
    <x-nav-link :href="route('operations.index')" :active="request()->routeIs('operations.index')" :badge="$this->getOperationsBadgeCount()">
        {{ __('Operations') }}
    </x-nav-link>
@else
    <x-responsive-nav-link :href="route('operations.index')" :active="request()->routeIs('operations.index')" :badge="$this->getOperationsBadgeCount()">
        {{ __('Operations') }}
    </x-responsive-nav-link>
@endif