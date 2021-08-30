<?php

namespace App\Http\Controllers;

use App\Models\Progression;
use App\Http\Resources\ProgressionResource;
use App\Models\ClassModel;
use Illuminate\Http\Request;
use Bouncer;

class ProgressionController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(ClassModel $class)
    {
        return ProgressionResource::collection($class->progressions);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request, ClassModel $class)
    {
        $request->validate([
            'name' => 'required|min:3',
            'color' => 'regex:/^(#[0-9a-zA-Z]{6})?$/i',
            'class_id' => 'required'
        ]);

        $params = $request->all();

        $progression = new Progression($params);
        Bouncer::authorize('update', $progression->class_);
        $progression->save();
        return new ProgressionResource($progression);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Progression  $progression
     * @return \Illuminate\Http\Response
     */
    public function show(Progression $progression)
    {
        Bouncer::authorize('update', $progression->class_);

        return new ProgressionResource($progression);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Progression  $progression
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Progression $progression)
    {
        Bouncer::authorize('update', $progression->class_);

        $request->validate([
            'name' => 'required|min:3',
            'color' => 'regex:/^(#[0-9a-zA-Z]{6})?$/i'
        ]);

        $progression->update($request->all());

        return new ProgressionResource($progression);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Progression  $progression
     * @return \Illuminate\Http\Response
     */
    public function destroy(Progression $progression)
    {
        Bouncer::authorize('update', $progression->class_);

        $progression->delete();

        return 204;
    }
}
