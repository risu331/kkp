<table>
    <thead>
        @if(Auth::user()->role == 'superadmin')
            <tr>
                <td style='text-align: left; white-space: nowrap;' colspan="3">Wilayah Kerja</td>
                <td colspan="2">:<b>{{ $data['fishing_data']->branch->name }}</b></td>
            </tr>
        @endif
            <tr>
                <td style='text-align: left; white-space: nowrap;' colspan="3">Nama Enumerator</td>
                <td colspan="2">:<b>{{ $data['fishing_data']->user_name }}</b></td>

                <td></td>
                <td></td>
                <td></td>

            </tr>
            
            <tr>
                <td style='text-align: left; white-space: nowrap;' colspan="3">Nama Kapal/GT</td>
                <td colspan="2">:<b>{{ $data['fishing_data']->ship->name }}/{{ $data['fishing_data']->gt }}</b></td>
                
                <td></td>
                <td></td>
                <td></td>
                
            </tr>
            <tr>
                <td style='text-align: left; white-space: nowrap;' colspan="3">Jenis Kapal</td>
                <td colspan="2">:<b>{{ $data['fishing_data']->ship->type_ship->type }}</b></td>
                
                <td></td>
                <td></td>
                <td></td>
            </tr>

            <tr>
                <td style='text-align: left; white-space: nowrap;' colspan="3">Lokasi Pendaratan</td>
                <td colspan="2">:<b>{{ $data['fishing_data']->landing_site->name }}</b></td>
                
                <td></td>
                <td></td>
                <td></td>
            </tr>
            
            <tr>
                <td style='text-align: left; white-space: nowrap;' colspan="3">Jenis Alat Tangkap</td>
                <td colspan="2">:<b>{{ $data['fishing_data']->fishing_gear->name }}</b></td>
                
                <td></td>
                <td></td>
                <td></td>
            </tr>

            <tr>
                <td style='text-align: left; white-space: nowrap;' colspan="3">Jumlah Hari Operasional</td>
                <td colspan="2">:<b>{{ $data['fishing_data']->operational_day }}</b></td>
                
                <td></td>
                <td></td>
                <td></td>
            </tr>
            
            <tr>
                <td style='text-align: left; white-space: nowrap;' colspan="3">Jumlah Hari Perjalanan</td>
                <td colspan="2">:<b>{{ $data['fishing_data']->travel_day }}</b></td>
            </tr>
            
            <tr>
                <td style='text-align: left; white-space: nowrap;' colspan="3">Jumlah Setting</td>
                <td colspan="2">:<b>{{ $data['fishing_data']->setting }}</b></td>
            </tr>

            <tr>
                <td style='text-align: left; white-space: nowrap;' colspan="3">Daerah Penangkapan Ikan</td>
                <td colspan="2">:<b>{{ $data['fishing_data']->area }}</b></td>
            </tr>

            <tr>
                <td style='text-align: left; white-space: nowrap;' colspan="3">Waktu Enumerasi</td>
                <td colspan="2">:<b>{{ date('d F Y', strtotime($data['fishing_data']->enumeration_time)) }}</b></td>
            </tr>

            <tr></tr>
            
            @php
                $type_fishs = App\Models\TypeFish::where('branch_id', $data['fishing_data']->branch_id)->get();
                $all_weight = 0;
            @endphp
            @foreach($type_fishs as $type_fish)
                @php
                    $weight = 0;
                    $amount = 0;
                    foreach($data['fishing_data']->data_collections as $data)
                    {
                        if($data->species_fish->type_fish_id == $type_fish->id)
                        {
                            $amount += $data->amount_fish;
                            $weight += $data->weight;
                            $all_weight += $data->weight;
                        }
                    } 
                @endphp
                <tr>
                    <td style='text-align: left; white-space: nowrap;' colspan="3">Tot. Hasil Tangkap {{ $type_fish->type }}</td>
                    <td colspan="1">:<b>{{ $weight }} Kg</b></td>
                    <td colspan="1">:<b>{{ $amount }} ekor</b></td>
                </tr>
            @endforeach
            <tr>
                <td style='text-align: left; white-space: nowrap;' colspan="3">Tot. Hasil Tangkap Ikan Lainnya</td>
                <td colspan="2">:<b>{{ $data['fishing_data']->total_other_fish }} Kg</b></td>
            </tr>
            <tr>
                <td style='text-align: left; white-space: nowrap;' colspan="3">Tot. Hasil Tangkap Ikan</td>
                <td colspan="2">:<b>{{ $all_weight + $data['fishing_data']->total_other_fish }} Kg</b></td>
            </tr>
    </thead>
</table>

<table>
    <tr>
        <th rowspan="2" style='text-align: center; white-space: nowrap; border: 2px solid black;'>NO</th>
        <th rowspan="2" style='text-align: center; white-space: nowrap; border: 2px solid black;'>Nama Lokal</th>
        <th rowspan="2" style='text-align: center; white-space: nowrap; border: 2px solid black;'>Nama Umum/Dagang</th>
        <th rowspan="2" style='text-align: center; white-space: nowrap; border: 2px solid black;'>Nama Ilmiah</th>
        <th colspan="3" style='text-align: center; white-space: nowrap; border: 2px solid black;'>Ukuran Tubuh</th>
        <th rowspan="2" style='text-align: center; white-space: nowrap; border: 2px solid black;'>Lebar Pari</th>
        <th colspan="3" style='text-align: center; white-space: nowrap; border: 2px solid black;'>Ukuran Sirip Punggung</th>
        <th rowspan="2" style='text-align: center; white-space: nowrap; border: 2px solid black;'>Panjang Sirip Dada</th>
        <th rowspan="2" style='text-align: center; white-space: nowrap; border: 2px solid black;'>Berat (kg)</th>
        <th rowspan="2" style='text-align: center; white-space: nowrap; border: 2px solid black;'>Jenis Kelamin</th>
        <th rowspan="2" style='text-align: center; white-space: nowrap; border: 2px solid black;'>Tingkat Kematangan Gonad</th>
        <th rowspan="2" style='text-align: center; white-space: nowrap; border: 2px solid black;'>Nama Kapal</th>
        <th rowspan="2" style='text-align: center; white-space: nowrap; border: 2px solid black;'>Alat Tangkap</th>
        <th rowspan="2" style='text-align: center; white-space: nowrap; border: 2px solid black;'>GT</th>
        <th rowspan="2" style='text-align: center; white-space: nowrap; border: 2px solid black;'>Daerah Penangkapan Ikan</th>
        <th rowspan="2" style='text-align: center; white-space: nowrap; border: 2px solid black;'>Trip Dilaut (hari)</th>
        <th rowspan="2" style='text-align: center; white-space: nowrap; border: 2px solid black;'>Panjang Klasper (cm)</th>
        <th rowspan="2" style='text-align: center; white-space: nowrap; border: 2px solid black;'>Jenis</th>
        <th rowspan="2" style='text-align: center; white-space: nowrap; border: 2px solid black;'>Status</th>
        <th rowspan="2" style='text-align: center; white-space: nowrap; border: 2px solid black;'>Jumlah Ekor</th>
        <th rowspan="2" style='text-align: center; white-space: nowrap; border: 2px solid black;'>Jumlah Anakan (ekor)</th>
        <th colspan="2" style='text-align: center; white-space: nowrap; border: 2px solid black;'>Ukuran Anak</th>
        <th rowspan="2" style='text-align: center; white-space: nowrap; border: 2px solid black;'>Harga Ekonomi per 1 Kg</th>
        <th rowspan="2" style='text-align: center; white-space: nowrap; border: 2px solid black;'>Total Harga Ekonomi</th>
        <th rowspan="2" style='text-align: center; white-space: nowrap; border: 2px solid black;'>Keterangan</th>
    </tr>

    <tr>
        <th style='text-align: center; white-space: nowrap; border: 2px solid black;'>SL (cm)</th>
        <th style='text-align: center; white-space: nowrap; border: 2px solid black;'>FL (cm)</th>
        <th style='text-align: center; white-space: nowrap; border: 2px solid black;'>TL (cm)</th>
        <th style='text-align: center; white-space: nowrap; border: 2px solid black;'>M (cm)</th>
        <th style='text-align: center; white-space: nowrap; border: 2px solid black;'>P (cm)</th>
        <th style='text-align: center; white-space: nowrap; border: 2px solid black;'>T (cm)</th>
        <th style='text-align: center; white-space: nowrap; border: 2px solid black;'>Min (cm)</th>
        <th style='text-align: center; white-space: nowrap; border: 2px solid black;'>Max (cm)</th>
    </tr>

    @php
        $no = 1;
    @endphp
    @foreach($data['fishing_data']->data_collections as $data_collection)
        <tr>
            <td style='text-align: center; white-space: nowrap; border: 2px solid black;'>{{ $no++ }}</td>
            <td style='text-align: center; white-space: nowrap; border: 2px solid black;'>{{ $data_collection->species_fish->local }}</td>
            <td style='text-align: center; white-space: nowrap; border: 2px solid black;'>{{ $data_collection->species_fish->general }}</td>
            <td style='text-align: center; white-space: nowrap; border: 2px solid black;'><i>{{ $data_collection->species_fish->species }}</i></td>
            <td style='text-align: center; white-space: nowrap; border: 2px solid black;'>{{ $data_collection->sl }}</td>
            <td style='text-align: center; white-space: nowrap; border: 2px solid black;'>{{ $data_collection->fl }}</td>
            <td style='text-align: center; white-space: nowrap; border: 2px solid black;'>{{ $data_collection->tl }}</td>
            <td style='text-align: center; white-space: nowrap; border: 2px solid black;'>{{ $data_collection->dw }}</td>
            <td style='text-align: center; white-space: nowrap; border: 2px solid black;'>{{ $data_collection->m }}</td>
            <td style='text-align: center; white-space: nowrap; border: 2px solid black;'>{{ $data_collection->p }}</td>
            <td style='text-align: center; white-space: nowrap; border: 2px solid black;'>{{ $data_collection->t }}</td>
            <td style='text-align: center; white-space: nowrap; border: 2px solid black;'>{{ $data_collection->mp }}</td>
            <td style='text-align: center; white-space: nowrap; border: 2px solid black;'>{{ $data_collection->weight }}</td>
            <td style='text-align: center; white-space: nowrap; border: 2px solid black;'>{{ $data_collection->gender }}</td>
            <td style='text-align: center; white-space: nowrap; border: 2px solid black;'>{{ $data_collection->gonad }}</td>
            <td style='text-align: center; white-space: nowrap; border: 2px solid black;'>{{ $data['fishing_data']->ship->name }}</td>
            <td style='text-align: center; white-space: nowrap; border: 2px solid black;'>{{ $data['fishing_data']->fishing_gear->name }}</td>
            <td style='text-align: center; white-space: nowrap; border: 2px solid black;'>{{ $data['fishing_data']->gt }}</td>
            <td style='text-align: center; white-space: nowrap; border: 2px solid black;'>{{ $data['fishing_data']->area }}</td>
            <td style='text-align: center; white-space: nowrap; border: 2px solid black;'>{{ ($data['fishing_data']->operational_day - $data['fishing_data']->travel_day) * $data['fishing_data']->setting }}</td>
            <td style='text-align: center; white-space: nowrap; border: 2px solid black;'>{{ $data_collection->clasp_length }}</td>
            <td style='text-align: center; white-space: nowrap; border: 2px solid black;'>{{ $data_collection->species_fish->type_fish->type }}</td>
            <td style='text-align: center; white-space: nowrap; border: 2px solid black;'><i>{{ $data_collection->species_fish->group }}</i></td>
            <td style='text-align: center; white-space: nowrap; border: 2px solid black;'>{{ $data_collection->amount_fish }}</td>
            <td style='text-align: center; white-space: nowrap; border: 2px solid black;'>{{ $data_collection->amount_child }}</td>
            <td style='text-align: center; white-space: nowrap; border: 2px solid black;'>{{ $data_collection->length_min_child }}</td>
            <td style='text-align: center; white-space: nowrap; border: 2px solid black;'>{{ $data_collection->length_max_child }}</td>
            <td style='text-align: center; white-space: nowrap; border: 2px solid black;'>{{ $data_collection->economy_price }}</td>
            <td style='text-align: center; white-space: nowrap; border: 2px solid black;'>{{ $data_collection->total_economy_price }}</td>
            <td style='text-align: center; white-space: nowrap; border: 2px solid black;'>{{ $data_collection->description }}</td>
        </tr>
    @endforeach
</table>