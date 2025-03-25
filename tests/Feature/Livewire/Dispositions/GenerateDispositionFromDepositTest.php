<?php

use App\Models\User;
use Livewire\Livewire;
use App\Models\Deposit;
use App\Models\Disposition;
use App\Models\StorageCell;
use App\Models\StorageYard;
use App\Models\DispositionUnit;
use Illuminate\Support\Facades\DB;
use App\Enums\OperationRelationEnum;
use App\Livewire\Deposits\DepositsTable;
use Illuminate\Foundation\Testing\DatabaseTransactions;

uses(DatabaseTransactions::class);

beforeEach(function () {
    $this->user = User::factory()->create();
    $this->actingAs($this->user);
    
    $this->storageYard = StorageYard::factory()->create();
    $this->storageCell = StorageCell::factory()->create([
        'sc_yard_id' => $this->storageYard->sy_id
    ]);
});

test('can generate disposition from selected deposits', function () {

    $storageCells = StorageCell::factory([
        'sc_yard_id' => $this->storageYard->sy_id
    ])->count(3)->create();

    $deposits = [];

    foreach ($storageCells as $cell) {
        $deposits[] = Deposit::factory(['dep_sc_id' => $cell->sc_id])->create();
    }

    $depositIds = collect($deposits)->pluck('dep_id')->toArray();

    Livewire::test(DepositsTable::class)
        ->set('dispositionCreationArray', $depositIds)
        ->set('relationTo', OperationRelationEnum::CARRIAGE->value)
        ->set('driver', 'Test Driver')
        ->set('carriageNumber', 'CAR-123')
        ->set('carNumber', 'AUTO-456')
        ->call('generateDispositionFromSelectedDeposits');

    $disposition = Disposition::latest('dis_id')->first();
    
    expect($disposition)->not->toBeNull()
        ->and($disposition->dis_relation_from)->toBe(OperationRelationEnum::YARD)
        ->and($disposition->dis_relation_to)->toBe(OperationRelationEnum::CARRIAGE)
        ->and($disposition->dis_yard_id)->toBe($this->storageYard->sy_id)
        ->and($disposition->dis_created_by_id)->toBe($this->user->id);
    
    $dispositionUnits = DispositionUnit::where('disu_dis_id', $disposition->dis_id)->get();

    expect($dispositionUnits)->toHaveCount(3);
    
    foreach ($deposits as $deposit) {
        $dispositionUnit = DispositionUnit::where('disu_container_number', $deposit->dep_number)->first();
        
        expect($dispositionUnit)->not->toBeNull()
            ->and($dispositionUnit->disu_container_gross_weight)->toBe($deposit->dep_gross_weight)
            ->and($dispositionUnit->disu_container_net_weight)->toBe($deposit->dep_net_weight)
            ->and($dispositionUnit->disu_container_tare_weight)->toBe($deposit->dep_tare_weight)
            ->and($dispositionUnit->disu_driver)->toBe('Test Driver')
            ->and($dispositionUnit->disu_carriage_number)->toBe('CAR-123')
            ->and($dispositionUnit->disu_car_number)->toBe('AUTO-456');
    }
});

test('cannot generate disposition from yard to yard', function () { // todo dobrze stestować
    $deposits = Deposit::factory()->count(3)->create();
    $depositIds = $deposits->pluck('dep_id')->toArray();

    Livewire::test(DepositsTable::class)
        ->set('dispositionCreationArray', $depositIds)
        ->set('relationTo', OperationRelationEnum::CARRIAGE->value)
        ->set('driver', 'Test Driver')
        ->set('carriageNumber', 'CAR-123')
        ->set('carNumber', 'AUTO-456')
        ->call('generateDispositionFromSelectedDeposits');

    $newDisposition = Disposition::query()
        ->where('dis_relation_from', OperationRelationEnum::YARD->value)
        ->where('dis_relation_to', OperationRelationEnum::CARRIAGE->value)
        ->where('dis_suggested_date', today())
        ->latest('dis_id')
        ->first();
    
    expect($newDisposition)->toBeNull();
});

test('cannot generate disposition from deposits from different yards', function () {
    $deposit = Deposit::factory(['dep_sc_id' => $this->storageCell->sc_id])->create();
    $depositId = $deposit->dep_id;

    
    $component = Livewire::test(DepositsTable::class)
        ->set('dispositionCreationArray', [$depositId])
        ->set('relationTo', OperationRelationEnum::YARD);

    $component->call('generateDispositionFromSelectedDeposits');

    $yardId = $this->storageYard->sy_id;
    $newDisposition = Disposition::where('dis_yard_id', $yardId)
        ->where('dis_relation_from', OperationRelationEnum::YARD->value)
        ->where('dis_relation_to', OperationRelationEnum::YARD->value)
        ->latest('dis_id')
        ->first();
    
    expect($newDisposition)->toBeNull();
});

test('validates required fields before generating disposition', function () {
    $deposits = Deposit::factory(2)->create([
        'dep_sc_id' => $this->storageCell->sc_id
    ]);
    
    $depositIds = $deposits->pluck('dep_id')->toArray();
    
    Livewire::test(DepositsTable::class)
        ->set('dispositionCreationArray', $depositIds)
        ->call('generateDispositionFromSelectedDeposits')
        ->assertHasErrors(['relationTo']);
});
