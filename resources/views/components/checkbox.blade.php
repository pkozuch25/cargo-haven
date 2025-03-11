@props(['disabled' => false, 'label' => '', 'id' => null])

<input type="checkbox" id="{{ $id }}"
{{ $disabled ? 'disabled' : '' }}
{!! $attributes->merge([
    'class' => 'rounded dark:bg-gray-900 border-gray-300 dark:border-gray-700 text-lime-600 shadow-sm ring-lime-500 dark:focus:ring-lime-600 dark:focus:ring-offset-gray-800'
    ]) !!}
>
<label class="ml-1" for="{{ $id }}">{{ $label }}</label>