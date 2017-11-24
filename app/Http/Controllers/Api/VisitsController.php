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
        $this->validateParameters($year);

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
        $this->validateParameters($year, $month);

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
        $this->validateParameters($year, $month, $day);

        $visits = LogbookEntry::year($year)
            ->month($month)
            ->day($day)
            ->groupVisitsByHour();

        return [
            'data' => [
                'visits' => $visits,
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
    protected function validateParameters($year, $month = null, $day = null)
    {
        $data = ['year' => $year];

        if ($month) {
            $data['month'] = $month;
        }

        if ($day) {
            $data['day'] = $day;
        }

        $validator = Validator::make($data, [
            'year' => 'required_with:month|digits:4|max:' . date('Y'),
            'month' => 'sometimes|required_with:day|numeric|min:1|max:12',
            'day' => 'sometimes|numeric|min:1|max:31'
        ])->validate();
    }
}
