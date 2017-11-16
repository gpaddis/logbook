<?php

namespace App\Http\Controllers;

use App\PatronCategory;
use Illuminate\Http\Request;

class PatronCategoryController extends Controller
{
    /**
     * PatronCategoryController constructor.
     */
    public function __construct()
    {
        $this->middleware('auth');

        $this->middleware('permission:manage patron categories')
        ->except('index', 'show');
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
     * 
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:25|unique:patron_categories,name',
            'abbreviation' => 'string|max:10|nullable|unique:patron_categories,abbreviation',
            'is_active' => 'boolean',
            'is_primary' => 'boolean',
            'notes' => 'string|nullable'
        ]);

        PatronCategory::create($request->all());

        return redirect()
        ->route('patron-categories.index')
        ->with('flash', 'The new category was saved in the database.');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\PatronCategory  $patronCategory
     * 
     * @return \Illuminate\Http\Response
     */
    public function show(PatronCategory $category)
    {
        return view('patron-categories.show', compact('category'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\PatronCategory  $patronCategory
     * 
     * @return \Illuminate\Http\Response
     */
    public function edit(PatronCategory $category)
    {
        return view('patron-categories.edit', compact('category'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\PatronCategory  $patronCategory
     *
     * @see : https://laracasts.com/discuss/channels/requests/problem-with-unique-field-validation-on-update?page=1
     *
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, PatronCategory $category)
    {
        $request->validate([
            'name' => 'required|string|max:25|unique:patron_categories,name,' . $category->id,
            'abbreviation' => 'string|max:10|nullable|unique:patron_categories,abbreviation,' . $category->id,
            'is_active' => 'boolean',
            'is_primary' => 'boolean',
            'notes' => 'string|nullable'
        ]);

        $category->update($request->all());

        return redirect()
        ->route('patron-categories.index')
        ->with('flash', 'Your changes were saved in the database.');
    }
}
