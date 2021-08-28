<?php

namespace App\Http\Controllers;

use App\Models\PublicHoliday;
use App\Models\HolidayZone;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\DB;
use Bouncer;

class PublicHolidayController extends Controller
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
        Bouncer::authorize('manage', PublicHoliday::class);

        if ($request->source == 'api') {
            $request->validate([
                'year' => 'required|integer|min:2020',
            ]);

            $ids = PublicHoliday::where(DB::raw('YEAR(day)'), $request->year)->get('id')->toArray();
            PublicHoliday::destroy($ids);

            $count = 0;
            $zones = HolidayZone::all();
            // TODO delete for current year
            foreach ($zones as $zone) {
                $response = Http::get("https://calendrier.api.gouv.fr/jours-feries/{$zone->name}/{$request->year}.json");
                $days = json_decode($response->body());
                foreach ($days as $date => $name) {
                    PublicHoliday::create([
                        'name' => $name,
                        'day'  =>  date_create_from_format('Y-m-d', $date),
                        'holiday_zone_id' => $zone->id
                    ]);
                    $count ++;
                }
            }
            return response()->json(['message' => "$count jours récupérés", 'status' => true], 201);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\PublicHoliday  $publicHoliday
     * @return \Illuminate\Http\Response
     */
    public function show(PublicHoliday $publicHoliday)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\PublicHoliday  $publicHoliday
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, PublicHoliday $publicHoliday)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\PublicHoliday  $publicHoliday
     * @return \Illuminate\Http\Response
     */
    public function destroy(PublicHoliday $publicHoliday)
    {
        //
    }
}
