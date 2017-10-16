<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\LogbookEntry;
use App\PatronCategory;
use Illuminate\Http\Request;
use App\Http\Requests\LiveCounterRequest;

class LiveCounterController extends Controller
{
    /**
     * LiveCounterController constructor.
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Display the visits count for all active patron categories.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $patronCategories = PatronCategory::active()
        ->select('id', 'name', 'is_primary')
        ->orderBy('is_primary', 'desc')
        ->get();

        $initialCount = $this->show();

        return view('logbook.livecounter.index', compact('patronCategories', 'initialCount'));
    }

    public function show()
    {
        return PatronCategory::active()
        ->withCount(['logbookEntries as visits_count' => function ($query) {
            $query->whereDate('visited_at', date('Y-m-d'));
        }
        ])
        ->get()
        ->pluck('visits_count', 'id');
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

        return $this->show();
    }

    /**
     * Remove today's most recent record in the database for the patron_category_id sent with the request.
     *
     * @param App\Http\Requests\LiveCounterRequest $request
     */
    public function subtract(LiveCounterRequest $request)
    {
        LogbookEntry::deleteLatestRecord(request('patron_category_id'));

        return $this->show();
    }
}
