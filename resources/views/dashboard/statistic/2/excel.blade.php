<table>
    <thead>
        <tr>
            <td style='text-align: center; white-space: nowrap;' colspan="4"> <b>STATISTIK DATA IKAN</b></td>
        </tr>
        <tr>
            <td style='text-align: center; white-space: nowrap;' colspan="4">Menampilkan data jenis ikan yang didaratkan per bulannya</td>
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
            <td style='text-align: left; white-space: nowrap;' colspan="2">Tahun:</td>
            <td style='text-align: left; white-space: nowrap;'><b>{{ $data['year'] }}</b></td>
        </tr>
        <tr>
            <td style='text-align: left; white-space: nowrap;' colspan="2">Bulan:</td>
            <td><b>{{ $data['month'] }}</b></td>
        </tr>
    </thead>
</table>


<table>
    <thead>
    <tr>
        <th style='text-align: center; white-space: nowrap; border: 2px solid black;'>No</th>
        <th style='text-align: left; white-space: nowrap; border: 2px solid black;'>Species</th>
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
        @php
            $weight += $data->weight ?? 0;
            $amount_fish += $data->amount_fish ?? 0;
        @endphp
        <tr>
            <td style='text-align: center; white-space: nowrap; border: 2px solid black;'>{{ $no++ }}</td>
            <td style='text-align: left; white-space: nowrap; border: 2px solid black;'><i>{{ $data->species ?? '0' }}</i></td>
            <td style='text-align: right; white-space: nowrap; border: 2px solid black;'>{{ $data->weight ?? '0' }}</td>
            <td style='text-align: right; white-space: nowrap; border: 2px solid black;'>{{ $data->amount_fish ?? '0' }}</td>
        </tr>
    @endforeach
    </tbody>
    <tfoot>
        <tr>
            <td colspan="2" style='text-align: center; white-space: nowrap; border: 2px solid black;'><b>GRAND TOTAL</b></td>
            <td style='text-align: right; white-space: nowrap; border: 2px solid black;'><b>{{ $weight }}</b></td>
            <td style='text-align: right; white-space: nowrap; border: 2px solid black;'><b>{{ $amount_fish }}</b></td>
        </tr>
    </tfoot>
</table>