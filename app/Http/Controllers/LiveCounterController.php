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
        $patron_categories = PatronCategory::active()
            ->with(['logbookEntries' => function ($query) {
                $query->where('start_time', Timeslot::now()->start());
            }])->orderBy('is_primary', 'desc')->get();
        // return $patron_categories;

        return view('logbook.livecounter.index', compact('patron_categories'));
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
     * @param Illuminate\Http\Request $request
     */
    public function add(Request $request)
    {
        $this->validate($request, [
            'patron_category_id' => 'required|exists:patron_categories,id',
            ]);

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
     * @param Illuminate\Http\Request $request
     */
    public function subtract(Request $request)
    {
        $this->validate($request, [
            'patron_category_id' => 'required|exists:patron_categories,id',
            ]);

        LogbookEntry::where('patron_category_id', request('patron_category_id'))
        ->orderBy('visited_at', 'desc')
        ->first()
        ->delete();

        return redirect()->route('livecounter.index');
    }
}
