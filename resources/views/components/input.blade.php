@props(['disabled' => false, 'label' => ''])

<x-input-label value="{{ $label }}" />
<input
    {{ $disabled ? 'disabled' : '' }}
    {!! $attributes->merge([
        'class' => 'border-gray-300 disabled:border-gray-600 disabled:bg-gray-700 disabled:text-gray-400 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:ring-lime-500 focus:border-lime-500 focus:ring-2 dark:focus:border-lime-500 dark:focus:ring-lime-500 rounded-md shadow-sm !important'
    ]) !!}
>
