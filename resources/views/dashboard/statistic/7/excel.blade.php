<table>
    <thead>
        <tr>
            <td style='text-align: center; white-space: nowrap;' colspan="5"> <b>STATISTIK FREKUENSI PANJANG</b></td>
        </tr>
        <tr>
            <td style='text-align: center; white-space: nowrap;' colspan="5">Menampilkan frekuensi panjang data individu</td>
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
        <tr>
            <td style='text-align: left; white-space: nowrap;' colspan="2">Species:</td>
            <td><b><i>{{ $data['species']->species }}</i></b></td>
        </tr>
    </thead>
</table>


<table>
    <thead>
    <tr>
        <th style='text-align: center; white-space: nowrap; border: 2px solid black;'>No</th>
        <th style='text-align: left; white-space: nowrap; border: 2px solid black;'>Kategori</th>
        <th style='text-align: right; white-space: nowrap; border: 2px solid black;'>Jumlah Individu (ekor)</th>
    </tr>
    </thead>
    <tbody>
    @php
        $no = 1;
        $total = 0;
    @endphp
    <tr>
        <td style='text-align: center; white-space: nowrap; border: 2px solid black;'>{{ $no++ }}</td>
        <td style='text-align: left; white-space: nowrap; border: 2px solid black;'>Born {{ $data['species']->born_start ?? 0 }} cm - {{ $data['species']->born_end ?? 0 }} cm (ekor)</td>
        <td style='text-align: right; white-space: nowrap; border: 2px solid black;'>{{ $data['speciesFishs'][0] }}</td>
    </tr>
    <tr>
        <td style='text-align: center; white-space: nowrap; border: 2px solid black;'>{{ $no++ }}</td>
        <td style='text-align: left; white-space: nowrap; border: 2px solid black;'>Mature Male {{ $data['species']->mature_male_start ?? 0 }} cm - {{ $data['species']->mega_spawner ?? 0 }} cm (ekor)</td>
        <td style='text-align: right; white-space: nowrap; border: 2px solid black;'>{{ $data['speciesFishs'][1] }}</td>
    </tr>
    <tr>
        <td style='text-align: center; white-space: nowrap; border: 2px solid black;'>{{ $no++ }}</td>
        <td style='text-align: left; white-space: nowrap; border: 2px solid black;'>Mature Female {{ $data['species']->mature_female_start ?? 0 }} cm - {{ $data['species']->mega_spawner ?? 0 }} cm (ekor)</td>
        <td style='text-align: right; white-space: nowrap; border: 2px solid black;'>{{ $data['speciesFishs'][2] }}</td>
    </tr>
    <tr>
        <td style='text-align: center; white-space: nowrap; border: 2px solid black;'>{{ $no++ }}</td>
        <td style='text-align: left; white-space: nowrap; border: 2px solid black;'>Mega Spawner {{ $data['species']->mega_spawner ?? 0 }} cm (ekor)</td>
        <td style='text-align: right; white-space: nowrap; border: 2px solid black;'>{{ $data['speciesFishs'][3] }}</td>
    </tr>
    <tr>
        <td style='text-align: center; white-space: nowrap; border: 2px solid black;'>{{ $no++ }}</td>
        <td style='text-align: left; white-space: nowrap; border: 2px solid black;'>Tidak Terkategori</td>
        <td style='text-align: right; white-space: nowrap; border: 2px solid black;'>
            @php
                $uncategory = $data['speciesFishs'][4] - $data['speciesFishs'][3] - $data['speciesFishs'][2] - $data['speciesFishs'][1] - $data['speciesFishs'][0];
            @endphp
            @if($uncategory > 0)
                {{ $uncategory }}
            @else
                0
            @endif
        </td>
    </tr>
    </tbody>
</table>