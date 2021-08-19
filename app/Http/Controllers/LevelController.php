<?php

namespace App\Http\Controllers;

use App\Models\Level;
use App\Http\Resources\LevelResource;
use Illuminate\Http\Request;

class LevelController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return LevelResource::collection(Level::all());
    }
}
