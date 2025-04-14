<table>
    <tr>
        <td colspan="3" style="width:160px;font-size:16px;">{{ __('Transshipment card export') }}</td>
    </tr>
    <tr>
        <td style="font-weight:bold;">{{ __('Generated: ') }}{{ date('d.m.Y H:i') }}</td>
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
