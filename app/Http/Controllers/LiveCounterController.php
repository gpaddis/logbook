<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\LogbookEntry;
use Timeslot\Timeslot;
use App\PatronCategory;
use Illuminate\Http\Request;
use App\Http\Requests\LiveCounterRequest;

class LiveCounterController extends Controller
{
    /**
     * ThreadsController constructor
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $today = Timeslot::create(Carbon::now()->startOfDay(), 24);

        $patronCategories = PatronCategory::active()
        ->with(['logbookEntries' => function ($query) use ($today) {
            $query->within($today->start(), $today->end());
        }])->orderBy('is_primary', 'desc')->get();
        // dd($patronCategories);

        return view('logbook.livecounter.index', compact('patronCategories'));
    }

    /**
     * Add a record in the database for the patron_category_id sent with the request.
     *
     * @param App\Http\Requests\LiveCounterRequest $request
     */
    public function add(LiveCounterRequest $request)
    {
        LogbookEntry::create([
            'patron_category_id' => request('patron_category_id'),
            'visited_at' => Carbon::now(),
            'recorded_live' => true
        ]);

        return redirect()->route('livecounter.index');
    }

    /**
     * Remove today's most recent record in the database for the patron_category_id sent with the request.
     *
     * @param App\Http\Requests\LiveCounterRequest $request
     */
    public function subtract(LiveCounterRequest $request)
    {
        LogbookEntry::deleteLatestRecord(
            Timeslot::create(Carbon::now()->startOfDay(), 24),
            request('patron_category_id')
        );

        return redirect()->route('livecounter.index');
    }
}
