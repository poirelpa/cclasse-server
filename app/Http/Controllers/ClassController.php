<?php

namespace App\Http\Controllers;

use App\Models\ClassModel;
use App\Http\Resources\ClassResource;
use Illuminate\Http\Request;

class ClassController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return ClassResource::collection(ClassModel::all());
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $class = ClassModel::create($request->all());
        $class->levels()->attach($request->input('levels'));
        return new ClassResource($class);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\ClassModel  $classModel
     * @return \Illuminate\Http\Response
     */
    public function show(ClassModel $class)
    {
        // return ClassModel::findOrFail($id);
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
        //$class = ClassModel::findOrFail($id);
        $class->update($request->all());

        return new ClassResource($class);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\ClassModel  $classModel
     * @return \Illuminate\Http\Response
     */
    public function destroy(ClassModel $class)
    {
        $class->delete();

        return 204;
    }
}
