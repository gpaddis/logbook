<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\LogbookEntry;
use App\Logbook\Entry;
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
        $patronCategories = PatronCategory::active()
        ->with(['logbookEntries' => function ($query) {
            $query->within(Carbon::now()->startOfDay(), Carbon::now()->endOfDay());
        }])->orderBy('is_primary', 'desc')->get();
        // dd($patronCategories);

        return view('logbook.livecounter.index', compact('patronCategories'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    // public function store(LiveCounterRequest $request)
    // {
    //     $timeslot = Timeslot::now();
    //     $patron_category_id = $request->input('id');

    //     if ($request->input('operation') == 'add') {
    //         Entry::add($patron_category_id, $timeslot);
    //     } else {
    //         Entry::subtract($patron_category_id, $timeslot);
    //     }

    //     return redirect()->route('livecounter.index');
    // }

    /**
     * Add an entry to the database for the patron category sent
     * with the request
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
     * Remove the most recent entry in the database for the patron category
     * sent with the request
     *
     * @param App\Http\Requests\LiveCounterRequest $request
     */
    public function subtract(LiveCounterRequest $request)
    {
        LogbookEntry::where('patron_category_id', request('patron_category_id'))
        ->orderBy('visited_at', 'desc')
        ->first()
        ->delete();

        return redirect()->route('livecounter.index');
    }
}
