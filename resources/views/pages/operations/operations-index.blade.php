@extends('layouts.mainLayout')

@section('title', __('Operations'))

@section('content')

<x-layout-component size=11>
    <div class="row">
        <div class="col-12">
            <x-panel>
                @livewire('operations.operations-table')
            </x-panel>
        </div>
    </div>
</x-layout-component>
@livewire('operations.perform-operation-modal')
@stop
