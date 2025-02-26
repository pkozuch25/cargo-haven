<x-modal id="add-edit-disposition-modal" size="fullscreen" title="{{ __('Add disposition') }}" icon="fa fa-file-text-o">
    <x-slot name="modalBody">
        <form>

            {{-- plac
            status
            relacja z
            relacja do
            uwagi
            sugerowana data --}}
            <div class="row">
                <div class="col-lg-2 col-md-4 col-12">
                    <x-input-label for="password" value="{{ __('Password') }}" />
                    <x-text-input wire:model='disposition.dis_relation_from'
                        id="password"
                        name="password"
                        type="password"
                        placeholder="{{ __('Password') }}"
                    />
                </div>
            </div>
        </form>
    </x-slot>
    <x-slot name="modalFooter">
    </x-slot>
</x-modal>
