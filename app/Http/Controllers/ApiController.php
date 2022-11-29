<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class ApiController extends Controller
{
    public function index()
    {
        $response = Http::get('https://api.mapbox.com/geocoding/v5/mapbox.places/106.87,-6.25?token=pk.eyJ1IjoiYXJrYW5mYXV6YW45MyIsImEiOiJja3U2djJtYjcycm00Mm5vcTh0bHJxMmh6In0.8p3Sy60ztY0-uY-UTZSFHQ');
        $data = $response->json();
        dd($data);
    }
}
