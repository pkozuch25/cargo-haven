@props(['modal' => null, 'title' => null, 'icon' => null, 'disabled' => null, 'permissions' => null, 'href' => null])

<x-button :icon="'ti ti-plus'" {{ $attributes->merge(['class' => 'btn-success ', 'style' => '']) }}  :modal="$modal" :disabled="$disabled" :permissions="$permissions" :href="$href">{{ ($slot != '') ? $slot :  __('Add') }}</x-button>