<?php

namespace App\Http\Controllers;

use App\PatronCategory;
use Illuminate\Http\Request;

class PatronCategoryController extends Controller
{
    /**
     * TODO: add a middleware in the constructor to only allow the admin
     * to access store(), delete() and such methods. All other users are
     * only allowed to see the index() and show() methods.
     */

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
        $patron_categories = PatronCategory::all();

        return view('patron-categories.index', compact('patron_categories'));
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
        $patronCategory = PatronCategory::create([
            'name' => request('name'),
            'abbreviation' => request('abbreviation')
        ]);

        return redirect()->route('patron-categories.index');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\PatronCategory  $patronCategory
     * @return \Illuminate\Http\Response
     */
    public function show(PatronCategory $patronCategory)
    {
        return view('patron-categories.show', compact('patronCategory'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\PatronCategory  $patronCategory
     * @return \Illuminate\Http\Response
     */
    public function edit(PatronCategory $patronCategory)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\PatronCategory  $patronCategory
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, PatronCategory $patronCategory)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\PatronCategory  $patronCategory
     * @return \Illuminate\Http\Response
     */
    public function destroy(PatronCategory $patronCategory)
    {
        //
    }
}
