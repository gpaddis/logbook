<?php

namespace App\Http\Controllers;

use App\Logbook\Entry;
use App\PatronCategory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

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
        $categories = PatronCategory::active()->get();

        // TODO: implement the method to return this array of timeslots
        $timeslots = [
        \App\Timeslot::now(),
        \App\Timeslot::now()->addHour(1),
        \App\Timeslot::now()->addHour(2),
        ];

        return view('logbook.create', compact('categories', 'timeslots'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $messages = [
            'min' => 'The count must be a positive number. Correct the fields in red and try again.',
        ];

        $validator = Validator::make($request->all(), [
            'entry.*.start_time' => 'required|date',
            'entry.*.end_time' => 'required|date|after:start_time',
            'entry.*.patron_category_id' => 'required|exists:patron_categories,id',
            'entry.*.count' => 'nullable|integer|min:1'
        ], $messages);

        if ($validator->fails()) {
            return redirect()->route('logbook.create')
            ->withErrors($validator)
            ->withInput();
        }

        // $this->validate($request, [
        //     'entry.*.start_time' => 'required|date',
        //     'entry.*.end_time' => 'required|date|after:start_time',
        //     'entry.*.patron_category_id' => 'required|exists:patron_categories,id',
        //     'entry.*.count' => 'nullable|integer|min:1'
        // ]);

        $count = 0;
        foreach ($request->input('entry.*') as $entry) {
            Entry::updateOrCreateIfNotNull($entry);
            
            $entry['count'] === null ?: $count++;
        }

        if ($count === 0) {
            return redirect()->route('logbook.create');
        }

        return redirect()->route('logbook.index');
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
