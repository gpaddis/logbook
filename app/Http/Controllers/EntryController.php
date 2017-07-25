<?php

namespace App\Http\Controllers;

use App\Logbook\Entry;
use App\PatronCategory;
use Illuminate\Http\Request;

class EntryController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $categories = PatronCategory::active()->get();

        // TODO: implement the method to return this array of timeslots
        $timeslots = [
            \App\Timeslot::now()->get(),
            \App\Timeslot::now()->addHour(1)->get(),
            \App\Timeslot::now()->addHour(2)->get(),
            \App\Timeslot::now()->addHour(3)->get(),
            \App\Timeslot::now()->addHour(4)->get(),
            \App\Timeslot::now()->addHour(5)->get(),
        ];

        return view('visitslog.index', compact('categories', 'timeslots'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
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
        // $this->validate($request, [
            // https://stackoverflow.com/questions/32092276/laravel-5-request-validate-multidimensional-array            
        // ]);

        dd($request->all());
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\VisitsLog  $visitsLog
     * @return \Illuminate\Http\Response
     */
    public function show(VisitsLog $visitsLog)
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
