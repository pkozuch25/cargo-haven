@props(['label' => ''])

<x-input-label value="{{ $label }}" />
<select {{ $attributes->merge(['class' => 'w-100 bg-gray-900 focus:ring-lime-500 dark:border-gray-700 dark:text-gray-300 focus:border-lime-500 focus:ring-2 dark:focus:border-lime-500 dark:focus:ring-lime-500 rounded-md shadow-sm']) }}>
    {{ $slot }}
</select>
