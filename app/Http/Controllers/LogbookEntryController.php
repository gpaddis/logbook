<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\LogbookEntry;
use Timeslot\Timeslot;
use App\PatronCategory;
use Illuminate\Http\Request;
use Timeslot\TimeslotCollection;
use App\Repositories\LogbookEntries;
use App\Http\Requests\LogbookUpdateFormRequest;

class LogbookEntryController extends Controller
{
    /**
     * LogbookEntry constructor
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index()
    {
        $aggregates = LogbookEntry::getAggregatesWithin(Carbon::now()->subWeek()->startOfWeek(), Carbon::now());

        $today = $aggregates->where('day', Carbon::now()->toDateString())->first()->visits ?? 0;
        $yesterday = $aggregates->where('day', Carbon::now()->subDay()->toDateString())->first()->visits ?? 0;
        $thisWeeksAverage = $aggregates->where('week', Carbon::now()->weekOfYear)->pluck('visits')->average();
        $lastWeeksAverage = $aggregates->where('week', Carbon::now()->subWeek()->weekOfYear)->pluck('visits')->average();

        return view('logbook.tabs.overview', [
            'today' => $today,
            'yesterday' => $yesterday,
            'thisWeeksAverage' => $thisWeeksAverage,
            'lastWeeksAverage' => $lastWeeksAverage,
        ]);
    }


    /**
     * Validate and store the visits submitted with the logbook update form.
     *
     * @param  LogbookUpdateForm $form
     *
     * @return Response
     */
    public function store(LogbookUpdateFormRequest $form)
    {
        $form->persist();

        return redirect()->route('logbook.index')
        ->with('flash', 'The data was saved in the logbook.');
    }

    /**
     * Show the form for creating a new resource or updating the existing resources.
     *
     * @return Response
     */
    public function update(Request $request)
    {
        $today = Carbon::now()->toDateString();

        $request->validate(['date' => 'date|before_or_equal:' . $today]);

        // Fetch date from the request or default to today if no date is passed.
        $date = $request->input('date') ?: $today;

        // TODO: fetch opening time from application settings
        $opening_time = Carbon::parse($date)->hour(9)->minute(0)->second(0);
        $timeslots = TimeslotCollection::create(Timeslot::create($opening_time, 3), 5);

        $patronCategories = PatronCategory::active()->with(['logbookEntries' => function ($query) use ($timeslots) {
            $query->within($timeslots->start(), $timeslots->end());
        }])->get();

        $formContent = $this->buildFormContent($timeslots, $patronCategories);

        return view('logbook.update', compact('timeslots', 'patronCategories', 'formContent'));
    }

    /**
     * Browse the Year tab containing data for the year selected.
     *
     * @param  Request $request
     *
     * @return Response
     */
    public function browseYear(Request $request)
    {
        // Browse the years with available data in the dropdown menu.

        $year = 2017; // The year selected, the one for which the data is calculated
        $depth = 3; // How many years do you want to compare?

        $years = LogbookEntry::selectRaw('YEAR(visited_at) as year')
        ->distinct()
        ->pluck('year');

        $visits = LogbookEntry::year($year)
        ->selectRaw('MONTH(visited_at) as month, count(*) as visits')
        ->groupBy('month')
        ->pluck('visits', 'month')
        ->sortBy('month');

        $days = LogbookEntry::year($year)
        ->selectRaw('DATE(visited_at) as day')
        ->distinct('days')
        ->get()
        ->count();

        $visitsByYear = LogbookEntry::getTotalVisitsByYear($year, $depth);

        return view('logbook.tabs.year', compact('visits', 'years', 'days', 'visitsByYear'));
    }

    /**
     * Build the content of the logbook form with the timeslots and patron categories passed.
     * Only existing visits count values are added to the array, which is structured as follows:
     * ['timeslot_no' => ['patron_category_id' => 'visits']];
     *
     * @param  TimeslotCollection $timeslots
     * @param  PatronCategory     $categories
     *
     * @return array
     */
    protected function buildFormContent($timeslots, $categories)
    {
        $content = [];

        foreach ($timeslots as $timeslotNo => $timeslot) {
            foreach ($categories as $category) {
                if ($visits = $category->logbookEntries
                    ->where('visited_at', '>=', $timeslot->start())
                    ->where('visited_at', '<=', $timeslot->end())
                    ->count()) {
                    $content[$timeslotNo][$category->id] = $visits;
                } else {
                    continue;
                }
            }
        }
        return $content;
    }
}
