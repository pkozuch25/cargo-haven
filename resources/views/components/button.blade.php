@props(['modal' => null, 'title' => null, 'icon' => null, 'disabled' => null, 'permissions' => null, 'href' => null])

@if (($permissions && !empty($permissions) && canany($permissions)) || (!$permissions || empty($permissions)))  
    
    @if ($href)
        <a href="{{ $href }}" {{ $attributes->merge(['class' => 'btn ', 'style' => '', 'title' => $title]) }} @if ($modal) data-bs-toggle="modal" data-bs-target="#{{ $modal }}" @endif {{ $disabled ? 'disabled' : '' }}>
            @if ($icon)
                <i class="{{ $icon }}"></i>
            @endif
            {{ $slot }}
        </a>
    @else
        <button type="button" {{ $attributes->merge(['class' => 'btn ', 'style' => '', 'title' => $title]) }} @if ($modal) data-bs-toggle="modal" data-bs-target="#{{ $modal }}" @endif {{ $disabled ? 'disabled' : '' }}>
            @if ($icon)
                <i class="{{ $icon }}"></i>
            @endif
            {{ $slot }}
        </button>
    @endif


@endif