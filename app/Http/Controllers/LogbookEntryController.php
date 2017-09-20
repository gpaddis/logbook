<?php

namespace App\Http\Controllers;

use App\LogbookEntry;
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
        LogbookEntry::create([
            'patron_category_id' => $form->patron_category_id,
            'visited_at' => $form->visited_at,
            'recorded_at' => Carbon::now()
            ]);

        return redirect()->route('logbook.index');
    }
}
