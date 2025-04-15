<table>
    <tr>
        <td colspan="3" style="width:160px;font-size:16px;">{{ __('Transshipment card export') }} {{ $transshipmentCard?->tc_number }}</td>
    </tr>
    <tr>
        <td style="font-weight:bold;">{{ __('Generated: ') }}{{ date('d.m.Y H:i') }}</td>
    </tr>
</table>

<table>
    <tr>
        <td style="font-weight:bold;">{{ __('Yard') }}</td>
        <td>{{ $transshipmentCard->storageYard?->sy_name ?? '-' }}</td>
    </tr>
    <tr>
        <td style="font-weight:bold;">{{ __('Relation from') }}</td>
        <td>{{ $transshipmentCard->tc_relation_from?->name() ?? '-' }}</td>
    </tr>
    <tr>
        <td style="font-weight:bold;">{{ __('Relation to') }}</td>
        <td>{{ $transshipmentCard->tc_relation_to?->name() ?? '-' }}</td>
    </tr>
    <tr>
        <td style="font-weight:bold;">{{ __('Created at') }}</td>
        <td>{{ $transshipmentCard->created_at?->format('Y-m-d H:i') ?? '-' }}</td>
    </tr>
    <tr>
        <td style="font-weight:bold;">{{ __('Created by') }}</td>
        <td>{{ $transshipmentCard->createdBy?->name ?? '-' }}</td>
    </tr>
    <tr>
        <td style="font-weight:bold;">{{ __('Status') }}</td>
        <td>{{ $transshipmentCard->tc_status?->name() ?? '-' }}</td>
    </tr>
    <tr>
        <td style="font-weight:bold;">{{ __('Notes') }}</td>
        <td>{{ $transshipmentCard->tc_notes ?? '-' }}</td>
    </tr>
</table>

<table style="margin-top: 20px;">
    <tr>
        <td colspan="12" style="font-weight:bold;font-size:14px;">{{ __('Transshipment card units') }}</td>
    </tr>
</table>

<table>
    <thead>
        <tr style="border:1px solid #000000;">
            <th style="font-weight:bold;word-wrap: break-word;border:1px solid #000000;text-align: center;width:250px">{{ __('Container number') }}</th>
            <th style="font-weight:bold;word-wrap: break-word;border:1px solid #000000;text-align: center;width:300px">{{ __('Operator') }}</th>
            <th style="font-weight:bold;word-wrap: break-word;border:1px solid #000000;text-align: center;width:150px">{{ __('Disposition') }}</th>
            <th style="font-weight:bold;word-wrap: break-word;border:1px solid #000000;text-align: center;width:150px">{{ __('Yard position') }}</th>    
            <th style="font-weight:bold;word-wrap: break-word;border:1px solid #000000;text-align: center;width:150px">{{ __('From carriage') }}</th>
            <th style="font-weight:bold;word-wrap: break-word;border:1px solid #000000;text-align: center;width:150px">{{ __('To carriage') }}</th>
            <th style="font-weight:bold;word-wrap: break-word;border:1px solid #000000;text-align: center;width:150px">{{ __('From truck') }}</th>
            <th style="font-weight:bold;word-wrap: break-word;border:1px solid #000000;text-align: center;width:150px">{{ __('To truck') }}</th>
            <th style="font-weight:bold;word-wrap: break-word;border:1px solid #000000;text-align: center;width:150px">{{ __('Gross weight') }}</th>
            <th style="font-weight:bold;word-wrap: break-word;border:1px solid #000000;text-align: center;width:150px">{{ __('Net weight') }}</th>
            <th style="font-weight:bold;word-wrap: break-word;border:1px solid #000000;text-align: center;width:150px">{{ __('Tare weight') }}</th>
            <th style="font-weight:bold;word-wrap: break-word;border:1px solid #000000;text-align: center;width:150px">{{ __('Created at') }}</th>
        </tr>
    </thead>
    <tbody>
        @foreach($data as $unit)
            <tr>  
                <td>{{ $unit->tcu_container_number ?? '-' }}</td>
                <td>{{ $unit->operator?->name ?? '-' }}</td>
                <td>{{ $unit->dispositionUnit?->disposition?->dis_number ?? '-' }}</td>
                <td>{{ $unit->tcu_yard_position ?? '-' }}</td>
                <td>{{ $unit->tcu_carriage_number_from ?? '-' }}</td>
                <td>{{ $unit->tcu_carriage_number_to ?? '-' }}</td>
                <td>{{ $unit->tcu_truck_number_from ?? '-' }}</td>
                <td>{{ $unit->tcu_truck_number_to ?? '-' }}</td>
                <td>{{ $unit->tcu_gross_weight ?? '-' }}</td>
                <td>{{ $unit->tcu_net_weight ?? '-' }}</td>
                <td>{{ $unit->tcu_tare_weight ?? '-' }}</td>
                <td>{{ $unit->created_at?->format('Y-m-d H:i') ?? '-' }}</td>
            </tr>
        @endforeach
    </tbody>
</table>
