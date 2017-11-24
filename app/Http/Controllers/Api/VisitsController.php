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
            'data' => [
                'visits' => LogbookEntry::getVisitsByYear($year)
            ]
        ];
    }

    /**
     * Return the visits count for a specific month, grouped by day.
     *
     * @param [type] $year
     * @param [type] $month
     * @return void
     */
    public function month($year, $month)
    {
        // TODO: validate

        return [
            'data' => [
                'visits' => LogbookEntry::getVisitsByMonth($year, $month)
            ]
        ];
    }

    /**
     * Return the visits count for a specific day, grouped by hour.
     *
     * @param [type] $year
     * @param [type] $month
     * @param [type] $day
     * @return void
     */
    public function day($year, $month, $day)
    {
        // TODO: validate
        $date = $year . '-' . $month . '-' . $day;

        return [
            'data' => [
                'visits' => LogbookEntry::getVisitsByDay($date)
            ]
        ];
    }
}
