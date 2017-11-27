<?php

namespace App\Http\Controllers\Api;

use App\LogbookEntry;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use Carbon\Carbon;

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

        $visits = LogbookEntry::year($year)
            ->groupVisitsByMonth();

        return [
            'data' => [
                'visits' => $visits,
                'label' => $year,
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

        $visits = LogbookEntry::year($year)
            ->month($month)
            ->groupVisitsByDay();

        return [
            'data' => [
                'visits' => $visits,
                'label' => Carbon::create($year, $month, 1)->format('F Y'),
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
                'label' => Carbon::create($year, $month, $day)->format('F j, Y'),
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
     * Validate the parameters passed to the routes by assembling the route
     * parameters passed and checking if they form a valid date.
     *
     * @param int $year
     * @param int $month
     * @param int $day
     * @return void
     */
    protected function validateParameters($year, $month = null, $day = null)
    {
        $formattedDate[] = $year;
        $formattedDate[] = $month ?? '01';
        $formattedDate[] = $day ?? '01';

        $data['formattedDate'] = implode('-', $formattedDate);

        Validator::make($data, [
            'formattedDate' => 'required|date|before:' . date('Y-m-d'),
        ])->validate();
    }
}
