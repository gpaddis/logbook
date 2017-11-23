<?php

namespace App\Http\Controllers\Api;

use App\LogbookEntry;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;

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
    public function year($year)
    {
        $data = ['year' => $year];

        $validator = Validator::make($data, [
            'year' => 'digits:4|max:' . date('Y')
        ]);

        if ($validator->fails()) {
            abort(422, 'Your request cannot be processed.');
        }

        return [
            'visits' => LogbookEntry::getTotalVisitsByYear($year)
        ];
    }

    /**
     * Return the visits count for a specific day, grouped by hour.
     *
     * @param [type] $day
     * @return void
     */
    public function day($day)
    {
        return LogbookEntry::getVisitsByDay($day);
    }
}
