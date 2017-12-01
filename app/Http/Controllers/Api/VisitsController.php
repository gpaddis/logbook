<?php

namespace App\Http\Controllers\Api;

use Carbon\Carbon;
use App\LogbookEntry;
use App\Http\Controllers\Controller;
use App\Http\Requests\Api\VisitsRequest;
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
     * Return the visits count for a specific year, grouped by month.
     *
     * @param VisitsRequest $request
     * @param int $year
     * @return array
     */
    public function year(VisitsRequest $request, $year)
    {
        $this->validateParameters($year);
        $period = $request->input('groupBy') ?? 'month';

        $visits = LogbookEntry::year($year)
            ->aggregateBy($period);

        return [
            'data' => [
                'visits' => $visits,
                'label' => $year,
                'period' => [
                    'year' => $year
                ],
                'groupedBy' => $period
            ]
        ];
    }

    /**
     * Return the visits count for a specific month, grouped by day.
     *
     * @param VisitsRequest $request
     * @param int $year
     * @param int $month
     * @return array
     */
    public function month(VisitsRequest $request, $year, $month)
    {
        $this->validateParameters($year, $month);
        $period = $request->input('groupBy') ?? 'day';

        $visits = LogbookEntry::year($year)
            ->month($month)
            ->aggregateBy($period);

        return [
            'data' => [
                'visits' => $visits,
                'label' => Carbon::create($year, $month, 1)->format('F Y'),
                'period' => [
                    'year' => $year,
                    'month' => $month
                ],
                'groupedBy' => $period
            ]
        ];
    }

    /**
     * Return the visits count for a specific day, grouped by hour.
     *
     * @param VisitsRequest $request
     * @param int $year
     * @param int $month
     * @param int $day
     * @return array
     */
    public function day(VisitsRequest $request, $year, $month, $day)
    {
        $this->validateParameters($year, $month, $day);
        $period = $request->input('groupBy') ?? 'hour';

        $visits = LogbookEntry::year($year)
            ->month($month)
            ->day($day)
            ->aggregateBy($period);

        return [
            'data' => [
                'visits' => $visits,
                'label' => Carbon::create($year, $month, $day)->format('F j, Y'),
                'period' => [
                    'year' => $year,
                    'month' => $month,
                    'day' => $day
                ],
                'groupedBy' => $period
            ]
        ];
    }

    /**
     * Validate the parameters passed to the routes by assembling them
     * and checking if they form a valid date.
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
            'formattedDate' => 'required|date|before:' . date('Y-m-d')
        ])->validate();
    }
}
