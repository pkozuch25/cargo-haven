<x-modal id="add-edit-disposition-modal" size="fullscreen" title="{{ $title }}" pillText="{{ $disposition?->dis_status?->name() }}" pillClass="{{ $disposition?->dis_status?->color() }}" icon="ti ti-file-text">
    <x-slot name="modalBody">
        @if ($disposition)
            <div class="row">
                <div class="col-lg-2">
                    <x-form-select :disabled="$this->dispositionHasUnits() || $this->dispositionWasCancelledOrFinalized()" :label="__('Yard')" wire:model.live='disposition.dis_yard_id'>
                        <option value="">{{ __("Select") }}</option>
                        @if($storageYards)
                            @foreach ($storageYards as $yard)
                                <option value="{{ $yard->sy_id }}">{{ $yard->sy_name }}</option>
                            @endforeach
                        @endif
                    </x-form-select>
                    <x-input-error :messages="$errors->get('disposition.dis_yard_id')" class="mt-2" />
                </div>
                <div class="col-lg-2">
                    <x-form-select :disabled="$this->dispositionHasUnits() || $this->dispositionWasCancelledOrFinalized()" :label="__('Relation from')" wire:model.live='disposition.dis_relation_from'>
                        <option value="">{{ __("Select") }}</option>
                        @if($relationFromFormAvailableRelations)
                            @foreach ($relationFromFormAvailableRelations as $relation)
                                <option value="{{$relation}}">{{ $relation->name() }}</option>
                            @endforeach
                        @endif
                    </x-form-select>
                    <x-input-error :messages="$errors->get('disposition.dis_relation_from')" class="mt-2" />
                </div>
                @if($disposition->dis_relation_from)
                    <div class="col-lg-2">
                        <x-form-select :disabled="$this->dispositionHasUnits() || $this->dispositionWasCancelledOrFinalized()" :label="__('Relation to')" wire:model='disposition.dis_relation_to'>
                            @if($relationToFormAvailableRelations)
                                @foreach ($relationToFormAvailableRelations as $relation)
                                    <option value="{{ $relation }}">{{ $relation->name() }}</option>
                                @endforeach
                            @endif
                        </x-form-select>
                        <x-input-error :messages="$errors->get('disposition.dis_relation_to')" class="mt-2" />
                    </div>
                @endif
                <div class="col-lg-2">
                    <x-input-full :label="__('Suggested date')" :disabled="$this->dispositionWasCancelledOrFinalized()" class="flatpickr" wire:model='disposition.dis_suggested_date' placeholder="{{ __('Suggested date') }}" />
                    <x-input-error :messages="$errors->get('disposition.dis_suggested_date')" class="mt-2" />
                </div>
                <div class="col-lg-2">
                    <x-textarea style="max-height: 150px" :disabled="$this->dispositionWasCancelledOrFinalized()" :label="__('Description / notes')" name="descTextArea" rows="2" wire:model='disposition.dis_notes'>
                    </x-textarea>
                </div>
                <div class="col-lg-2">
                    <x-input-label>{{ __('Operators') }}</x-input-label>
                    <div wire:ignore>
                        <select @if($this->dispositionWasCancelledOrFinalized()) disabled @endif name="dis_operators" multiple>
                            @if (count($disposition->operators) > 0)
                                @foreach ($disposition->operators as $operator)
                                    <option value="{{ $operator->id }}" selected>{{ $operator->name }}</option>
                                @endforeach
                            @endif
                        </select>
                    </div>
                    <x-input-error :messages="$errors->get('dispositionOperators')" class="mt-2" />
                </div>
            </div>
            <div class="row mt-3">
                <div class="col-sm-11">
                    @if(!$this->dispositionWasCancelledOrFinalized())
                        <x-button class="btn-success mr-2" wire:click='save'>
                            {{ __('Save') }}
                        </x-button>
                    @endif
                    @if($this->canChangeStatus())
                        <x-button class="btn-success" wire:click='changeStatus'>
                            {{ $disposition->dis_status?->changeStatusButtonText() }}
                        </x-button>
                    @endif
                </div>
                <div class="col-sm-1">
                    @if($disposition->dis_status == App\Enums\DispositionStatusEnum::NOT_CONFIRMED)
                        <x-button class="btn-danger float-right" wire:click='cancelDispositionConfirm'>
                            {{ __('Cancel') }}
                        </x-button>
                    @endif
                </div>
            </div>
            @if ($edit && $disposition != null)
                @livewire('dispositions.disposition-units.disposition-units-form-table', ['disposition' => $disposition->dis_id])
            @endif
        @endif
    </x-slot>
    <x-slot name="modalFooter">
    </x-slot>
    @push('javascript')
        <script>
            $(function() {
                iniSelect2();
                operatorsOnChange();
            })

            window.addEventListener('iniSelect2', event => {
                $(function() {
                    $('select[name="dis_operators"]').val(event.detail[0].operators);
                    iniSelect2();
                    operatorsOnChange();
                })
            });

            function iniSelect2() {
                $('select[name="dis_operators"]').select2({
                    dropdownParent: $('#add-edit-disposition-modal'),
                    ajax: {
                        url: "{{ route('get-operators-to-select2') }}",
                        dataType: 'json',
                        delay: 250,
                        data: function(params) {
                            return {
                                search: params.term
                            };
                        },
                        processResults: function(response) {
                            return {
                                results: response
                            };
                        },
                        cache: true
                    }
                });
            }

            function operatorsOnChange() {
                var dataBefore = $('select[name="dis_operators"]').select2("val");
                $('select[name="dis_operators"]').on('change', function(e) {
                    var dataAfter = $('select[name="dis_operators"]').select2("val");
                    @this.checkIfOperatorIsAssignedToOtherDispositions(dataAfter, dataBefore);
                    dataBefore = dataAfter;
                });
            }
        </script>
    @endpush
</x-modal>
