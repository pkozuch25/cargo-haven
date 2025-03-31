@props(['disabled' => false, 'label' => ''])

<x-input-label value="{{ $label }}"/>
<div  {{ $disabled ? 'disabled' : '' }}
    {!! $attributes->merge([
        'class' => 'w-100 border-1 p-2 min-h-[38px] block disabled:border-gray-600 disabled:bg-gray-700 disabled:text-gray-400 border-gray-700 dark:border-gray-700 dark:bg-gray-800 dark:text-gray-300 focus:ring-lime-500 focus:border-lime-500 focus:ring-2 dark:focus:border-lime-500 dark:focus:ring-lime-500 rounded-md shadow-sm !important'
    ]) !!}
>
    {{ $slot }}
</div>