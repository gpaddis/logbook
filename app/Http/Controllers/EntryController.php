<?php

namespace App\Http\Controllers;

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
        $entries = Entry::all();

        return view('logbook.index', compact('entries'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        // TODO: implement the method to return this array of timeslots
        $timeslots = [
        \App\Timeslot::now(),
        \App\Timeslot::now()->addHour(1),
        \App\Timeslot::now()->addHour(2),
        ];

        return view('logbook.create', compact('timeslots'));
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
     * Show the form for editing the specified resource.
     *
     * @param  \App\VisitsLog  $visitsLog
     * @return \Illuminate\Http\Response
     */
    public function edit(VisitsLog $visitsLog)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\VisitsLog  $visitsLog
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, VisitsLog $visitsLog)
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
