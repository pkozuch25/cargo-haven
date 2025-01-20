<div class="col-md-3 col-sm-5 col-lg-2 col-8 d-flex align-items-center">
    <label class="float-start me-1">{{ __('Show') }}</label>
        <select wire:model.live='perPage' class="form-select float-start">
            <option value="10">10</option>
            <option value="25">25</option>
            <option value="50">50</option>
            <option value="100">100</option>
        </select>
    <label class="float-start ms-1" style="margin-right:5px;">{{ __('entries') }}</label>
</div>