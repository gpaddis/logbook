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
        $this->validateFields($year);

        return [
            'data' => [
                'visits' => LogbookEntry::getVisitsByYear($year),
                'period' => [
                    'year' => $year
                ],
                'groupedBy' => 'month'
            ]
        ];
    }

    /**
     * Return the visits count for a specific month, grouped by day.
     *
     * @param int $year
     * @param int $month
     * @return void
     */
    public function month($year, $month)
    {
        $this->validateFields($year, $month);

        return [
            'data' => [
                'visits' => LogbookEntry::getVisitsByMonth($year, $month),
                'period' => [
                    'year' => $year,
                    'month' => $month
                ],
                'groupedBy' => 'day'
            ],
        ];
    }

    /**
     * Return the visits count for a specific day, grouped by hour.
     *
     * @param int $year
     * @param int $month
     * @param int $day
     * @return void
     */
    public function day($year, $month, $day)
    {
        $this->validateFields($year, $month, $day);

        $date = $year . '-' . $month . '-' . $day;

        return [
            'data' => [
                'visits' => LogbookEntry::getVisitsByDay($date),
                'period' => [
                    'year' => $year,
                    'month' => $month,
                    'day' => $day
                ],
                'groupedBy' => 'hour'
            ]
        ];
    }

    /**
     * Validate the parameters passed to the routes.
     *
     * @param int $year
     * @param int $month
     * @param int $day
     * @return void
     */
    protected function validateFields($year, $month = null, $day = null)
    {
        $data = [
            'year' => $year,
            'month' => $month,
            'day' => $day
        ];

        $validator = Validator::make($data, [
            'year' => 'required_with:month|digits:4|max:' . date('Y'),
            'month' => 'nullable|required_with:day|numeric|min:1|max:12',
            'day' => 'nullable|numeric|min:1|max:31'
        ]);

        if ($validator->fails()) {
            abort(422, 'Your request cannot be processed.');
        }
    }
}
