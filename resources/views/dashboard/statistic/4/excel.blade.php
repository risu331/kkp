<table>
    <thead>
        <tr>
            <td style='text-align: center; white-space: nowrap;' colspan="5"> <b>STATISTIK ALAT TANGKAP</b></td>
        </tr>
        <tr>
            @if($data['status'] == 'appendiks')
                <td style='text-align: center; white-space: nowrap;' colspan="5">Menampilkan komposisi jenis appendiks yang didaratkan berdasarkan jenis alat tangkapnya</td>
            @else
                <td style='text-align: center; white-space: nowrap;' colspan="5">Menampilkan komposisi jenis non-appendiks yang didaratkan berdasarkan jenis alat tangkapnya</td>
            @endif
        </tr>
    </thead>
</table>

<table>
    <thead>
        @if(Auth::user()->role == 'superadmin')
            <tr>
                <td style='text-align: left; white-space: nowrap;' colspan="2">Wilayah Kerja:</td>
                <td><b>{{ $data['branch']->name }}</b></td>
            </tr>
        @endif
        <tr>
            <td style='text-align: left; white-space: nowrap;' colspan="2">Tanggal:</td>
            <td><b>{{ date('F d, Y', strtotime($data['start_date'])) }} - {{ date('F d, Y', strtotime($data['end_date'])) }}</b></td>
        </tr>
        <tr>
            <td style='text-align: left; white-space: nowrap;' colspan="2">Status:</td>
            <td><b>{{ $data['status'] }}</b></td>
        </tr>
    </thead>
</table>


<table>
    <thead>
    <tr>
        <th style='text-align: center; white-space: nowrap; border: 2px solid black;'>No</th>
        <th style='text-align: left; white-space: nowrap; border: 2px solid black;'>Species</th>
        <th style='text-align: left; white-space: nowrap; border: 2px solid black;'>Alat Tangkap</th>
        <th style='text-align: right; white-space: nowrap; border: 2px solid black;'>Berat Tubuh</th>
        <th style='text-align: right; white-space: nowrap; border: 2px solid black;'>Individual Total (ekor)</th>
    </tr>
    </thead>
    <tbody>
    @php
        $no = 1;
        $weight = 0;
        $amount_fish = 0;
    @endphp
    @foreach($data['speciesFishs'] as $key => $data)
        <tr>
            <td style='text-align: center; white-space: nowrap; border: 2px solid black;' rowspan="{{ count($data->fishing_gears) }}">{{ $no++ }}</td>
            <td style='text-align: left; white-space: nowrap; border: 2px solid black;' rowspan="{{ count($data->fishing_gears) }}"><i>{{ $data->species ?? '0' }}</i></td>
            <td style='text-align: left; white-space: nowrap; border: 2px solid black;'>{{ $data->fishing_gears[0]['gear'] ?? '' }}</td>
            <td style='text-align: right; white-space: nowrap; border: 2px solid black;'>{{ $data->fishing_gears[0]['weight'] ?? '0' }}</td>
            <td style='text-align: right; white-space: nowrap; border: 2px solid black;'>{{ $data->fishing_gears[0]['amount_fish'] ?? '0' }}</td>
        </tr>
            @foreach($data->fishing_gears as $key => $fishing_gear)
                @php
                    $weight += $fishing_gear['weight'] ?? 0;
                    $amount_fish += $fishing_gear['amount_fish'] ?? 0;
                @endphp
                @if($key != 0)
                    <tr>
                        <td style='text-align: left; white-space: nowrap; border: 2px solid black;'>{{ $fishing_gear['gear'] ?? '' }}</td>
                        <td style='text-align: right; white-space: nowrap; border: 2px solid black;'>{{ $fishing_gear['weight'] ?? '0' }}</td>
                        <td style='text-align: right; white-space: nowrap; border: 2px solid black;'>{{ $fishing_gear['amount_fish'] ?? '0' }}</td>
                    </tr>
                @endif
            @endforeach
    @endforeach
    </tbody>
    <tfoot>
        <tr>
            <td colspan="3" style='text-align: center; white-space: nowrap; border: 2px solid black;'><b>GRAND TOTAL</b></td>
            <td style='text-align: right; white-space: nowrap; border: 2px solid black;'><b>{{ $weight }}</b></td>
            <td style='text-align: right; white-space: nowrap; border: 2px solid black;'><b>{{ $amount_fish }}</b></td>
        </tr>
    </tfoot>
</table>