<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Logbook\Entry;
use App\PatronCategory;
use Illuminate\Http\Request;
use App\Http\Requests\LogbookUpdateForm;

class EntryController extends Controller
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
        $entries = Entry::with('patron_category')->latest()->get();

        return view('logbook.index', compact('entries'));
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
        $opening_time = Carbon::parse($date)->hour(11)->minute(0)->second(0);
        $timeslots = \App\TimeslotCollection::make($opening_time, 5)->getCollection();

        $patron_categories = \App\PatronCategory::active()->with(['logbookEntries' => function ($query) use ($opening_time) {
            $query->whereDate('start_time', $opening_time->toDateString());
        }])->get();

        // return $patron_categories;

        return view('logbook.update', compact('timeslots', 'patron_categories'));
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
     * Display the specified resource.
     *
     * @param  \App\VisitsLog  $visitsLog
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\VisitsLog  $visitsLog
     * @return \Illuminate\Http\Response
     */
    public function destroy(VisitsLog $visitsLog)
    {
        //
    }
}
