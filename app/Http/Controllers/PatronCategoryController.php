<?php

namespace App\Http\Controllers;

use App\Counters\PatronCategory;
use Illuminate\Http\Request;

class PatronCategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $categories = PatronCategory::get();

        return view('settings.patron-categories.index', compact('categories'));
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
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Counters\PatronCategory  $patronCategory
     * @return \Illuminate\Http\Response
     */
    public function show(PatronCategory $patronCategory)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Counters\PatronCategory  $patronCategory
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
     * @param  \App\Counters\PatronCategory  $patronCategory
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, PatronCategory $patronCategory)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Counters\PatronCategory  $patronCategory
     * @return \Illuminate\Http\Response
     */
    public function destroy(PatronCategory $patronCategory)
    {
        //
    }
}
