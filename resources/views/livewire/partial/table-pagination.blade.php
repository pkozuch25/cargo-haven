<div class="mx-0 row">
    @php
        $data = $this->queryRefresh;
    @endphp
    <div class="col-sm-12 col-md-6 mt-2 d-flex align-items-center justify-content-start">
            {{ __('Showing') }} 
            {{ (($data->links()->paginator->currentPage() - 1) * ( $data->links()->paginator->perPage() )) + 1 }} 
            {{ __('to') }}
            {{ $data->links()->paginator->currentPage() * $data->links()->paginator->perPage() }} 
            {{ __('of') }}
            {{ $data->links()->paginator->total() }} {{ __('entries') }}
        </div>
    
    <div class="col-sm-12 col-md-6 mt-2 d-flex align-items-center justify-content-end">
            <ul class="pagination">{{ $data->links('pagination::bootstrap-5') }}</ul>
    </div>
</div>