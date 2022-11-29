@extends('layouts.app')

@section('content')
<div class="container fluid">
    <h1 style="text-align: center; font-weight: 600; font-size: 40px">My Travelling Response</h1>
    <div class="row">
        <div class="col-md-7">
            <div class="card-header bg-white shadow-sm">
                <font style="font-weight: bold;">Maps location</font>
            </div>
            <div id="mapbox" style="width: 100%; height: 80vh;">
        </div>
        Rating:
            <form action="/home">
                <input type="checkbox" name="rating1" value="1"> 1
                <input type="checkbox" name="rating2"> 2
                <input type="checkbox" name="rating3"> 3
                <input type="checkbox" name="rating4"> 4
                <input type="checkbox" name="rating5"> 5
            
                <button type="submit" class="btn btn-info text-white btn-block">Telusuri</button>
                <a href="/home" class="btn btn-dark text-white btn-block">Clear</a>
            </form>
    </div>
    <div class="col-md-5">
        <div class="card-header bg-white shadow-sm">
            <font style="font-weight: bold;">Form Input</font>
        </div>
        {{-- success alert --}}
        @if (session()->has('success'))
            <div class="alert alert-success" role="alert">
                {{ session('success') }}
            </div>
        @endif
        <div class="card-body mt-3">
            <form action="/record-data" method="POST">
                @csrf
                <div class="form-group">
                    <label>Title</label>
                    <input type="text" name="title" class="form-control @error('title') is-invalid  @enderror" placeholder="masukkan judul..." value="{{ old('title') }}">
                    @error('title')
                        <div class="invalid-feedback">
                            {{ $message }}
                        </div>
                    @enderror
                </div>
                <div class="form-group">
                    <label>Alamat</label>
                    <input type="text" name="alamat" id="alamat" class="form-control @error('alamat') is-invalid  @enderror" placeholder="masukkan Alamat..." value="{{ old('alamat') }}">
                    @error('alamat')
                        <div class="invalid-feedback">
                            {{ $message }}
                        </div>
                    @enderror
                </div>
                <div class="form-group">
                    <label>Coordinate</label>
                    <input type="text" name="coordinate" id="coordinate" class="form-control @error('coordinate') is-invalid  @enderror" placeholder="Coordinate" value="{{ old('coordinate') }}">
                    @error('coordinate')
                        <div class="invalid-feedback">
                            {{ $message }}
                        </div>
                    @enderror
                </div>
                <div class="form-group">
                    <label>Rating</label>
                    <select name="rating" class="form-control @error('rating') is-invalid  @enderror">
                        <option value="">-- Select Rating --</option>
                        <option value="1"> ⭐</option>
                        <option value="2"> ⭐ ⭐ </option>
                        <option value="3"> ⭐ ⭐ ⭐</option>
                        <option value="4"> ⭐ ⭐ ⭐ ⭐</option>
                        <option value="5"> ⭐ ⭐ ⭐ ⭐ ⭐</option>
                    </select>
                    @error('rating')
                        <div class="invalid-feedback">
                            {{ $message }}
                        </div>
                    @enderror
                </div>
                <div class="form-group">
                    <label>Description</label>
                    <textarea name="description" class="form-control @error('description') is-invalid  @enderror" placeholder="isi deskripsi...">{{ old('description') }}</textarea>
                    @error('description')
                        <div class="invalid-feedback">
                            {{ $message }}
                        </div>
                    @enderror
                </div>
                <div class="form-group mt-3">
                    <button type="submit" class="btn btn-primary text-white btn-block">Simpan</button>
                    <input type="reset" value="Clear" class="btn btn-dark text-white btn-block">
                </div>
            </form>
            <table class="table border-3 mt-3">
                <thead>
                    <tr>
                        <th>No</th>
                        <th scope="col">Title</th>
                        <th scope="col">Address</th>
                        <th scope="col">Rating</th>
                        <th scope="col">Description</th>
                    </tr>
                </thead>
                <tbody>
                    @if ($records->count())
                        @foreach ($records as $record)
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td>{{ $record->title }}</td>
                            <td>{{ $record->alamat }}</td>
                            <td>
                                @if ($record->rating == 1)
                                ⭐
                                @elseif ($record->rating == 2)
                                ⭐⭐
                                @elseif ($record->rating == 3)
                                ⭐⭐⭐
                                @elseif ($record->rating == 4)
                                ⭐⭐⭐⭐
                                @elseif ($record->rating == 5)
                                ⭐⭐⭐⭐⭐
                                @endif
                            </td>
                            <td>{{ $record->description }}</td>
                        </tr>
                        @endforeach
                    @else
                        <tr>
                            <td colspan="5" style="text-align: center">Data Tidak Ada</td>
                        </tr>
                    @endif
                    
                </tbody>
            </table>
        </div>
    </div>
</div>
@push('script')
    {{-- //import axios --}}
    <script>

        mapboxgl.accessToken = 'pk.eyJ1IjoiYXJrYW5mYXV6YW45MyIsImEiOiJja3U2djJtYjcycm00Mm5vcTh0bHJxMmh6In0.8p3Sy60ztY0-uY-UTZSFHQ';
        const map = new mapboxgl.Map({
        container: 'mapbox', // container ID
        style: 'mapbox://styles/mapbox/streets-v11', // style URL
        center: [106.87, -6.25], // starting position [lng, lat]
        zoom: 9 // starting zoom
        });
        // edited

        var geocoder = new MapboxGeocoder({
            accessToken: mapboxgl.accessToken,
            mapboxgl: mapboxgl,
            marker:true,
            placeholder: 'Masukan kata kunci...',
            zoom:20
        });
        map.addControl(
            geocoder
        );
        geocoder.on('result', ({ result }) => {
                const coordinate = `${result.geometry.coordinates[0]}, ${result.geometry.coordinates[1]}`;
                const address = result.place_name;
                console.log(address)
                console.log(coordinate)
                
                document.getElementById("alamat").value = address
                document.getElementById("coordinate").value = coordinate
                map.getSource('single-point').setData(result.geometry);
            })

        const places = {!! json_encode($records->toArray()) !!}
        const rating1 = [];     const rating2 = [];     const rating3 = [];
        const rating4 = [];     const rating5 = [];

        places.forEach(data => {
            const lngLat = data.coordinate.split(', ');
            const icon = {
                'type' : 'Feature',
                'geometry' : {
                    'type' : 'Point',
                    'coordinates' : [ lngLat[0], lngLat[1] ]
                },
                'properties' : {
                    'marker-size' : 'medium'
                }
            }
            const rank = data.rating;
            if(rank === '1'){
                rating1.push(icon);
            }else if(rank === '2'){
                rating2.push(icon);
            }else if(rank === '3') {
                rating3.push(icon);
            }else if(rank === '4') {
                rating4.push(icon);
            }else if(rank === '5') {
                rating5.push(icon);
            }
        });

        map.on('click', async (e) => {
            map.flyTo({
                center: [e.lngLat.lng, e.lngLat.lat],
                esential : true,
                speed : 1.5
            })
            map.getSource('single-point').setData({
                "type": "FeatureCollection",
                "features": [{
                    "type": "Feature",
                    "properties" : { "name" : "Null Island"},
                    "geometry": {
                        "type" : "Point",
                        "coordinates" : [ e.lngLat.lng, e.lngLat.lat]
                    }
                }]
            })
        })
    </script>
@endpush
@endsection
