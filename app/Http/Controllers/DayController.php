<?php

namespace App\Http\Controllers;

use App\Models\ClassModel;
use App\Models\Day;
use App\Http\Resources\DayResource;
use App\Http\Resources\WeekResource;
use Illuminate\Http\Request;

class DayController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @param  \App\Models\ClassModel  $class
     * @return \Illuminate\Http\Response
     */
    public function index(ClassModel $class)
    {
        return DayResource::collection($class->days);
    }
    /**
     * Display a listing of the resource.
     *
     * @param  \App\Models\ClassModel  $class
     * @return \Illuminate\Http\Response
     */
    public function indexWeeks(ClassModel $class)
    {
        return WeekResource::collection(
            $class->days()
            ->selectRaw('week as id, week as week, min(day) as starts_on, max(day) as ends_on, count(day) as number_of_days')
            ->groupBy('week')->get()
        );
    }

    /**
     * Show the form for creating a new resource.
     *
     * @param  \App\Models\ClassModel  $class
     * @return \Illuminate\Http\Response
     */
    public function create(ClassModel $class)
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\ClassModel  $class
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request, ClassModel $class)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\ClassModel  $class
     * @param  \App\Models\Day  $day
     * @return \Illuminate\Http\Response
     */
    public function show(ClassModel $class, Day $day)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\ClassModel  $class
     * @param  \App\Models\Day  $day
     * @return \Illuminate\Http\Response
     */
    public function edit(ClassModel $class, Day $day)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\ClassModel  $class
     * @param  \App\Models\Day  $day
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, ClassModel $class, Day $day)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\ClassModel  $class
     * @param  \App\Models\Day  $day
     * @return \Illuminate\Http\Response
     */
    public function destroy(ClassModel $class, Day $day)
    {
        //
    }
}
