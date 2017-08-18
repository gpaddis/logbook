<?php

namespace App\Http\Controllers;

use App\Timeslot;
use App\Logbook\Entry;
use App\PatronCategory;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
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
            $query->where('start_time', \App\Timeslot::now()->start());
            }])->orderBy('is_primary', 'desc')->get();
        // return $patron_categories;

        return view('logbook.livecounter.index', compact('patron_categories'));
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
    public function store(LiveCounterRequest $request)
    {
        $timeslot = Timeslot::now();
        $patron_category_id = $request->input('id');

        if ($request->input('operation') == 'add') {
            Entry::add($patron_category_id, $timeslot);
        } else {
            Entry::subtract($patron_category_id, $timeslot);
        }

        return redirect()->route('livecounter.index');
    }


    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
