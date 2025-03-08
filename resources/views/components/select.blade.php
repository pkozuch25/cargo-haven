@props(['label' => '', 'disabled' => false])

<x-input-label value="{{ $label }}" />
<select {{ $disabled ? 'disabled' : '' }}
    {{ $attributes->merge(['class' => 'form-select disabled:border-gray-600 disabled:bg-gray-700 disabled:text-gray-400 bg-gray-800 focus:ring-lime-500 focus:border-lime-500 focus:ring-2 dark:focus:border-lime-500 dark:focus:ring-lime-500 rounded-md shadow-sm']) }}>
    {{ $slot }}
</select>
