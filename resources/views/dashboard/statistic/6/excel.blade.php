<table>
    <thead>
        <tr>
            <td style='text-align: center; white-space: nowrap;' colspan="5"> <b>STATISTIK HARGA EKONOMI</b></td>
        </tr>
        <tr>
            <td style='text-align: center; white-space: nowrap;' colspan="5">Menampilkan berapa total harga ekonomi</td>
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
        <th style='text-align: right; white-space: nowrap; border: 2px solid black;'>Total Harga Ekonomi Rp.</th>
    </tr>
    </thead>
    <tbody>
    @php
        $no = 1;
        $total = 0;
    @endphp
    @foreach($data['speciesFishs'] as $key => $data)
        @php
            $total += $data->economy ?? 0;
        @endphp
        <tr>
            <td style='text-align: center; white-space: nowrap; border: 2px solid black;'>{{ $no++ }}</td>
            <td style='text-align: left; white-space: nowrap; border: 2px solid black;'><i>{{ $data->species ?? '0' }}</i></td>
            <td style='text-align: right; white-space: nowrap; border: 2px solid black;'>{{ "Rp " . number_format($data->economy,2,',','.') }}</td>
        </tr>
    @endforeach
    </tbody>
    <tfoot>
        <tr>
            <td colspan="2" style='text-align: center; white-space: nowrap; border: 2px solid black;'><b>GRAND TOTAL</b></td>
            <td style='text-align: right; white-space: nowrap; border: 2px solid black;'><b>{{ "Rp " . number_format($total,2,',','.') }}</b></td>
        </tr>
    </tfoot>
</table>