<?php

namespace App\Http\Controllers;

use App\Models\SchoolHoliday;
use App\Models\Academy;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\DB;
use Bouncer;

class SchoolHolidayController extends Controller
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
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        Bouncer::authorize('manage', SchoolHoliday::class);

        if ($request->source == 'api') {
            $request->validate([
                'year' => 'required|integer|min:2020',
            ]);

            $year = $request->year;
            $year2 = $request->year + 1;

            $ids = SchoolHoliday::whereBetween(
                'starts_on',
                [ date_create("$year-08-15"), date_create("$year2-08-15")]
            )->get('id')->toArray();
            SchoolHoliday::destroy($ids);

            $count = 0;
            $academies = Academy::all();
            // TODO delete for current year
            foreach ($academies as $academy) {
                $response = Http::get("https://data.education.gouv.fr/api/records/1.0/search/?dataset=fr-en-calendrier-scolaire&q=&rows=20&refine.annee_scolaire=$year-$year2&refine.location={$academy->name}");
                $holidays = json_decode($response->body())->records;
                foreach ($holidays as $holiday) {
                    if (!isset($holiday->fields->end_date)) {
                        $holiday->fields->end_date = "$year2-08-31";
                    }
                    SchoolHoliday::create([
                        'name' => $holiday->fields->description,
                        'starts_on'  =>  date_create_from_format(
                            'Y-m-d',
                            substr($holiday->fields->start_date, 0, 10)
                        )->modify('+1 day'),
                        'ends_on'  =>  date_create_from_format('Y-m-d', substr($holiday->fields->end_date, 0, 10)),
                        'academy_id' => $academy->id
                    ]);
                    $count ++;
                }
            }
            return response()->json(['message' => "$count vacances récupérés", 'status' => true], 201);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\SchoolHoliday  $schoolHoliday
     * @return \Illuminate\Http\Response
     */
    public function show(SchoolHoliday $schoolHoliday)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\SchoolHoliday  $schoolHoliday
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, SchoolHoliday $schoolHoliday)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\SchoolHoliday  $schoolHoliday
     * @return \Illuminate\Http\Response
     */
    public function destroy(SchoolHoliday $schoolHoliday)
    {
        //
    }
}
