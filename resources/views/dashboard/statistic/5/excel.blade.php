<table>
    <thead>
        <tr>
            <td style='text-align: center; white-space: nowrap;' colspan="7"> <b>STATISTIK JENIS KELAMIN</b></td>
        </tr>
        <tr>
            <td style='text-align: center; white-space: nowrap;' colspan="7">Menampilkan komposisi jenis ikan berdasarkan jenis kelamin (jantan/betina) per spesiesnya</td>
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
            <th style='text-align: center; white-space: nowrap; border: 2px solid black;' rowspan="2">No</th>
            <th style='text-align: left; white-space: nowrap; border: 2px solid black;' rowspan="2">Species</th>
            <th style='text-align: center; white-space: nowrap; border: 2px solid black;' colspan="2">Jumlah Ind (ekor)</th>
            <th style='text-align: center; white-space: nowrap; border: 2px solid black;' colspan="3">Kategori TKG</th>
        </tr>
        <tr>
            <td style='text-align: right; white-space: nowrap; border: 2px solid black;'>Jantan</td>
            <td style='text-align: right; white-space: nowrap; border: 2px solid black;'>Betina</td>
            <td style='text-align: right; white-space: nowrap; border: 2px solid black;'>1</td>
            <td style='text-align: right; white-space: nowrap; border: 2px solid black;'>2</td>
            <td style='text-align: right; white-space: nowrap; border: 2px solid black;'>3</td>
        </tr>
    </thead>
    <tbody>
    @php
        $no = 1;
        $j = 0;
        $b = 0;
        $tkg1 = 0;
        $tkg2 = 0;
        $tkg3 = 0;
    @endphp
    @foreach($data['speciesFishs'] as $key => $data)
        @php
            $j += $data->j;
            $b += $data->b;
            $tkg1 += $data->tkg1;
            $tkg2 += $data->tkg2;
            $tkg3 += $data->tkg3;
        @endphp
        <tr>
            <td style='text-align: center; white-space: nowrap; border: 2px solid black;'>{{ $no++ }}</td>
            <td style='text-align: left; white-space: nowrap; border: 2px solid black;'><i>{{ $data->species ?? '0' }}</i></td>
            <td style='text-align: right; white-space: nowrap; border: 2px solid black;'>{{ $data->j ?? '0' }}</td>
            <td style='text-align: right; white-space: nowrap; border: 2px solid black;'>{{ $data->b ?? '0' }}</td>
            <td style='text-align: right; white-space: nowrap; border: 2px solid black; width: 50px;'>{{ $data->tkg1 ?? '0' }}</td>
            <td style='text-align: right; white-space: nowrap; border: 2px solid black; width: 50px;'>{{ $data->tkg2 ?? '0' }}</td>
            <td style='text-align: right; white-space: nowrap; border: 2px solid black; width: 50px;'>{{ $data->tkg3 ?? '0' }}</td>
        </tr>
    @endforeach
    </tbody>
    <tfoot>
        <tr>
            <td colspan="2" style='text-align: center; white-space: nowrap; border: 2px solid black;'><b>GRAND TOTAL</b></td>
            <td style='text-align: right; white-space: nowrap; border: 2px solid black;'><b>{{ $j }}</b></td>
            <td style='text-align: right; white-space: nowrap; border: 2px solid black;'><b>{{ $b }}</b></td>
            <td style='text-align: right; white-space: nowrap; border: 2px solid black;'><b>{{ $tkg1 }}</b></td>
            <td style='text-align: right; white-space: nowrap; border: 2px solid black;'><b>{{ $tkg2 }}</b></td>
            <td style='text-align: right; white-space: nowrap; border: 2px solid black;'><b>{{ $tkg3 }}</b></td>
        </tr>
    </tfoot>
</table>