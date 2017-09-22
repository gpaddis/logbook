<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\LogbookEntry;
use Timeslot\Timeslot;
use App\PatronCategory;
use Illuminate\Http\Request;
use Timeslot\TimeslotCollection;
use App\Http\Requests\LogbookUpdateForm;

class LogbookEntryController extends Controller
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
        $entries = LogbookEntry::with('patron_category')->latest()->get();

        return view('logbook.index', compact('entries'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(LogbookUpdateForm $form)
    {
        $form->persist();

        return redirect()->route('logbook.index');
    }

    /**
     * Show the form for creating a new resource or updating the existing resources.
     *
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request)
    {
        $today = Carbon::now()->toDateString();

        $this->validate($request, [
            'date' => 'date|before_or_equal:' . $today
            ]);

        // Fetch date from the request or default to today if no date is passed.
        $date = $request->input('date') ?: $today;

        // TODO: fetch opening time from application settings
        $opening_time = Carbon::parse($date)->hour(10)->minute(0)->second(0);
        $timeslots = TimeslotCollection::create(Timeslot::create($opening_time), 8);

        $patron_categories = PatronCategory::active()->with(['logbookEntries' => function ($query) use ($timeslots) {
            $query->within($timeslots->start(), $timeslots->end());
        }])->get();

        // dd($patron_categories);

        return view('logbook.update', compact('timeslots', 'patron_categories'));
    }
}
