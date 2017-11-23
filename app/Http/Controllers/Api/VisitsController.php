<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;

class VisitsController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Return structured information about the year's visits.
     *
     * @param int $year
     * @return void
     */
    public function year(int $year)
    {
        return ['Year' => $year];
    }
}
