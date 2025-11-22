<?php

namespace App\Http\Controllers;

use App\Models\Song;
use Illuminate\Http\Request;

class SongController extends Controller
{
    public function getAllSongs()
    {
        return response([
            'songs' => Song::all(),

        ], 200);
    }
}
