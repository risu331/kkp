<table>
    <thead>
        <tr>
            <td style='text-align: center; white-space: nowrap;' colspan="7"> <b>STATISTIK KOMPOSISI HASIL TANGKAP IKAN</b></td>
        </tr>
        <tr>
            <td style='text-align: center; white-space: nowrap;' colspan="7">Menampilkan data ikan yang didaratkan berdasarkan lokasi pendaratan dan jenis ikan</td>
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
            <td style='text-align: left; white-space: nowrap;' colspan="2">Lokasi Pendaratan:</td>
            <td><b>{{ $data['landing_site']->name }}</b></td>
        </tr>
    </thead>
</table>


<table>
    <thead>
    <tr>
        <th style='text-align: center; white-space: nowrap; border: 2px solid black;'>No</th>
        <th style='text-align: left; white-space: nowrap; border: 2px solid black;'>Species</th>
        <th style='text-align: right; white-space: nowrap; border: 2px solid black;'><b>Total Length (cm)</b></th>
        <th style='text-align: right; white-space: nowrap; border: 2px solid black;'><b>Pre-caudal Length (cm)</b></th>
        <th style='text-align: right; white-space: nowrap; border: 2px solid black;'><b>Lebar Tubuh (cm)</b></th>
        <th style='text-align: right; white-space: nowrap; border: 2px solid black;'>Berat Tubuh (Kg)</th>
        <th style='text-align: right; white-space: nowrap; border: 2px solid black;'>Individual Total (ekor)</th>
    </tr>
    </thead>
    <tbody>
    @php
        $no = 1;
        $pt = 0;
        $ps = 0;
        $lt = 0;
        $weight = 0;
        $amount_fish = 0;
    @endphp
    @foreach($data['speciesFishs'] as $key => $data)
        @php
            $pt += $data->pt ?? 0;
            $ps += $data->ps ?? 0;
            $lt += $data->lt ?? 0;
            $weight += $data->weight ?? 0;
            $amount_fish += $data->amount_fish ?? 0;
        @endphp
        <tr>
            <td style='text-align: center; white-space: nowrap; border: 2px solid black;'>{{ $no++ }}</td>
            <td style='text-align: left; white-space: nowrap; border: 2px solid black;'><i>{{ $data->species ?? '0' }}</i></td>
            <td style='text-align: right; white-space: nowrap; border: 2px solid black;'>{{ $data->pt ?? '0' }}</td>
            <td style='text-align: right; white-space: nowrap; border: 2px solid black;'>{{ $data->ps ?? '0' }}</td>
            <td style='text-align: right; white-space: nowrap; border: 2px solid black;'>{{ $data->lt ?? '0' }}</td>
            <td style='text-align: right; white-space: nowrap; border: 2px solid black;'>{{ $data->weight ?? '0' }}</td>
            <td style='text-align: right; white-space: nowrap; border: 2px solid black;'>{{ $data->amount_fish ?? '0' }}</td>
        </tr>
    @endforeach
    </tbody>
    <tfoot>
        <tr>
            <td colspan="2" style='text-align: center; white-space: nowrap; border: 2px solid black;'><b>GRAND TOTAL</b></td>
            <td style='text-align: right; white-space: nowrap; border: 2px solid black;'><b>{{ $pt }}</b></td>
            <td style='text-align: right; white-space: nowrap; border: 2px solid black;'><b>{{ $ps }}</b></td>
            <td style='text-align: right; white-space: nowrap; border: 2px solid black;'><b>{{ $lt }}</b></td>
            <td style='text-align: right; white-space: nowrap; border: 2px solid black;'><b>{{ $weight }}</b></td>
            <td style='text-align: right; white-space: nowrap; border: 2px solid black;'><b>{{ $amount_fish }}</b></td>
        </tr>
    </tfoot>
</table>