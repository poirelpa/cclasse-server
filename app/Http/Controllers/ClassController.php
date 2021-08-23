<?php

namespace App\Http\Controllers;

use App\Models\ClassModel;
use App\Http\Resources\ClassResource;
use Illuminate\Http\Request;
use Bouncer;

class ClassController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return ClassResource::collection(ClassModel::whereUserCan('read')->get());
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        Bouncer::authorize('create', ClassModel::class);
        $request->validate([
            'year' => 'required|integer|min:2020',
            'levels' => 'required',
        ]);

        $params = $request->all();
        $params['user_id'] = auth()->user()->id;

        $class = ClassModel::create($params);
        $class->levels()->attach($request->input('levels'));

        return new ClassResource($class->fresh('levels'));
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\ClassModel  $classModel
     * @return \Illuminate\Http\Response
     */
    public function show(ClassModel $class)
    {
        Bouncer::authorize('read', $class);
        
        return new ClassResource($class);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\ClassModel  $classModel
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, ClassModel $class)
    {
        Bouncer::authorize('update', $class);

        $request->validate([
            'year' => 'required|integer|min:2020',
            'levels' => 'required',
        ]);

        $class->update($request->all());

        $class->levels()->sync($request->input('levels'));

        return new ClassResource($class->fresh('levels'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\ClassModel  $classModel
     * @return \Illuminate\Http\Response
     */
    public function destroy(ClassModel $class)
    {
        Bouncer::authorize('delete', $class);

        $class->delete();

        return 204;
    }
}
