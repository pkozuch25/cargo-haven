<select {{ $attributes->merge(['class' => 'form-select focus:ring-lime-500 focus:border-lime-500 focus:ring-2 dark:focus:border-lime-500 dark:focus:ring-lime-500 rounded-md shadow-sm']) }}>
    {{ $slot }}
</select>
