<?php

namespace App\Http\Controllers;

use App\Models\ClassModel;
use App\Models\Academy;
use App\Models\Day;
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
            'days' => 'required',
            'academy_id' => 'required'
        ]);

        $params = $request->all();
        $params['user_id'] = auth()->user()->id;

        $class = ClassModel::create($params);
        $class->levels()->attach($request->input('levels'));

        // init days based upon academy_id
        $academy = Academy::find($request->academy_id);
        $year2 = $class->year + 1;
        $schoolHolidays = $academy->schoolHolidays()
            ->whereBetween(
                'starts_on',
                [date_create("{$class->year}-06-15"), date_create("$year2-08-14")]
            )->get();
        $publicHolidays = $academy->holidayZone->publicHolidays()
            ->whereBetween(
                'day',
                [date_create("{$class->year}-08-16"), date_create("$year2-08-14")]
            )->get();
        $week = 0;
        $yearWeek = -1;
        for (
            $day = date_create("{$class->year}-08-16");
            $day < date_create("$year2-08-14");
            $day->modify("+1 day")
        ) {
            $create = true;
            if (!in_array($day->format('w'), $request->days)) {
                $create = false;
            }
            if ($create) {
                foreach ($publicHolidays as $ph) {
                    if ($ph->day == $day->format('Y-m-d')) {
                        $create = false;
                        break;
                    }
                }
            }
            if ($create) {
                foreach ($schoolHolidays as $sh) {
                    if ($sh->starts_on <= $day->format('Y-m-d') && $sh->ends_on >= $day->format('Y-m-d')) {
                        $create = false;
                        break;
                    }
                }
            }
            if ($create) {
                $w = $day->format('W');
                if ($w != $yearWeek) {
                    $yearWeek = $w;
                    $week ++;
                }
                Day::create([
                    'class_id' => $class->id,
                    'day' => $day,
                    'week' => $week
                ]);
            }
        }

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
