@extends('layouts.mainLayout')

@section('title', __('Transshipment cards'))

@section('content')

<x-layout-component size=11>
  <div class="row">
        <div class="col-12">
            <x-page-title>
                <i data-feather="file-text"></i>{{ __('Transshipment cards') }}
            </x-page-title>
            <x-panel>
                @livewire('transshipment-cards.transshipment-cards-table')
            </x-panel>
        </div>
  </div>
</x-layout-component>
@livewire('transshipment-cards.show-transshipment-card-details-modal')
@stop
