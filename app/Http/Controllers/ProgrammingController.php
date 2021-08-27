<?php

namespace App\Http\Controllers;

use App\Models\Programming;
use App\Http\Resources\ProgrammingResource;
use App\Models\ClassModel;
use Illuminate\Http\Request;
use Bouncer;

class ProgrammingController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|min:3',
            'color' => 'regex:/^(#[0-9a-zA-Z]{6})?$/i',
            'class_id' => 'required'
        ]);

        $params = $request->all();

        $programming = new Programming($params);
        Bouncer::authorize('update', $programming->class_);
        $programming->save();
        return new ProgrammingResource($programming);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Programming  $programming
     * @return \Illuminate\Http\Response
     */
    public function show(Programming $programming)
    {
        Bouncer::authorize('update', $programming->class_);

        return new ProgrammingResource($programming);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Programming  $programming
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Programming $programming)
    {
        Bouncer::authorize('update', $programming->class_);

        $request->validate([
            'name' => 'required|min:3',
            'color' => 'regex:/^(#[0-9a-zA-Z]{6})?$/i'
        ]);

        $programming->update($request->all());

        return new ProgrammingResource($programming);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Programming  $programming
     * @return \Illuminate\Http\Response
     */
    public function destroy(Programming $programming)
    {
        Bouncer::authorize('update', $programming->class_);

        $programming->delete();

        return 204;
    }
}
