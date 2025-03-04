@props(['disabled' => false])

<input
    placeholder="{{ __('Search...') }}"
    {{ $disabled ? 'disabled' : '' }}
    {!! $attributes->merge([
        'class' => 'block mt-1 w-full border-gray-300 dark:border-gray-700 dark:bg-gray-800 dark:text-white-100 dark:placeholder-white focus:ring-lime-500 focus:border-lime-500 focus:ring-2 dark:focus:border-lime-500 dark:focus:ring-lime-500 rounded-md shadow-sm !important'
    ]) !!}
>
