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
        return view('patron-categories.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        // dd($request->all());
        $request->validate([
                'name' => 'required|string|max:25|unique:patron_categories,name',
                'abbreviation' => 'string|max:10|nullable|unique:patron_categories,abbreviation',
                'is_active' => 'boolean',
                'is_primary' => 'boolean'
            ]);

        PatronCategory::create($request->all());

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
        $category = PatronCategory::find($patronCategory);

        return $category;
        return view('patron-categories.edit', compact($category));
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
