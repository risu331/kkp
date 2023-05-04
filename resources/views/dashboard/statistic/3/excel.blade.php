<table>
    <thead>
        <tr>
            <td style='text-align: center; white-space: nowrap;' colspan="5"> <b>STATISTIK STATUS APPENDIKS</b></td>
        </tr>
        <tr>
            <td style='text-align: center; white-space: nowrap;' colspan="5">Menampilkan data komposisi hasil tangkapan berdasarkan status appendiks dan non-appendiks</td>
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
    </thead>
</table>


<table>
    <thead>
    <tr>
        <th style='text-align: center; white-space: nowrap; border: 2px solid black;'>No</th>
        <th style='text-align: left; white-space: nowrap; border: 2px solid black;'>Species</th>
        <th style='text-align: left; white-space: nowrap; border: 2px solid black;'>Status</th>
        <th style='text-align: right; white-space: nowrap; border: 2px solid black;'>Berat Tubuh</th>
        <th style='text-align: right; white-space: nowrap; border: 2px solid black;'>Individual Total (ekor)</th>
    </tr>
    </thead>
    <tbody>
    @php
        $no = 1;
        $weight = 0;
        $amount_fish = 0;
        $weight_appendiks = 0;
        $amount_fish_appendiks = 0;
        $weight_nonappendiks = 0;
        $amount_fish_nonappendiks = 0;
    @endphp
    @foreach($data['speciesFishs'] as $key => $data)
        @php
            $weight += $data->weight ?? 0;
            $amount_fish += $data->amount_fish ?? 0;
            if($data->group == 'appendiks')
            {
                $weight_appendiks += $data->weight ?? 0;
                $amount_fish_appendiks += $data->amount_fish ?? 0;
            } else {
                $weight_nonappendiks += $data->weight ?? 0;
                $amount_fish_nonappendiks += $data->amount_fish ?? 0;
            }
        @endphp
        <tr>
            <td style='text-align: center; white-space: nowrap; border: 2px solid black;'>{{ $no++ }}</td>
            <td style='text-align: left; white-space: nowrap; border: 2px solid black;'><i>{{ $data->species ?? '0' }}</i></td>
            <td style='text-align: left; white-space: nowrap; border: 2px solid black;'><i>{{ $data->group ?? '0' }}</i></td>
            <td style='text-align: right; white-space: nowrap; border: 2px solid black;'>{{ $data->weight ?? '0' }}</td>
            <td style='text-align: right; white-space: nowrap; border: 2px solid black;'>{{ $data->amount_fish ?? '0' }}</td>
        </tr>
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

<table>
    <thead>
        <tr>
            <th style='text-align: left; white-space: nowrap; border: 2px solid black;'>Status</th>
            <th style='text-align: right; white-space: nowrap; border: 2px solid black;'>Berat Tubuh</th>
            <th style='text-align: right; white-space: nowrap; border: 2px solid black;'>Individual Total (ekor)</th>
        </tr>
    </thead>
    <tbody>
        <tr>
            <td style='text-align: left; white-space: nowrap; border: 2px solid black;'><b>Appendiks</b></td>
            <td style='text-align: right; white-space: nowrap; border: 2px solid black;'><b>{{ $weight_appendiks }}</b></td>
            <td style='text-align: right; white-space: nowrap; border: 2px solid black;'><b>{{ $amount_fish_appendiks }}</b></td>
        </tr>
        <tr>
            <td style='text-align: left; white-space: nowrap; border: 2px solid black;'><b>Non-appendiks</b></td>
            <td style='text-align: right; white-space: nowrap; border: 2px solid black;'><b>{{ $weight_nonappendiks }}</b></td>
            <td style='text-align: right; white-space: nowrap; border: 2px solid black;'><b>{{ $amount_fish_nonappendiks }}</b></td>
        </tr>
    </tbody>
</table>