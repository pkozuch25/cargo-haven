<h3 {{ $attributes->merge(['class' => 'page-title mb-2 text-sm font-medium leading-5 text-gray-900 dark:text-gray-100 transition duration-150 ease-in-out', 'style' => 'display: flex; align-items: center; gap: 10px; font-size: 20px;']) }}>
    {{ $slot }}
</h3>
