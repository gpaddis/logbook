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
        $lastAvailableDay = LogbookEntry::lastAvailableDay()->visits ?? 0;
        $thisWeeksAverage = $aggregates->where('week', Carbon::now()->weekOfYear)->pluck('visits')->average();
        $lastWeeksAverage = $aggregates->where('week', Carbon::now()->subWeek()->weekOfYear)->pluck('visits')->average();

        return view('logbook.tabs.overview', compact('today', 'lastAvailableDay', 'thisWeeksAverage', 'lastWeeksAverage'));
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

        return redirect()
        ->back()
        ->with('flash', 'The data was saved in the logbook.');
    }

    /**
     * Show the form for creating a new resource or updating the existing resources.
     *
     * @return Response
     */
    public function update(Request $request)
    {
        $today = date('Y-m-d');

        $request->validate(['date' => 'date|before_or_equal:' . $today]);

        // Fetch date from the request or default to today if no date is passed.
        $date = $request->input('date') ?: $today;
        $previousDay = Carbon::parse($date)->subDay()->toDateString();
        $nextDay = $date !== $today ? Carbon::parse($date)->addDay()->toDateString() : null;

        // TODO: fetch opening time from application settings
        $opening_time = Carbon::parse($date)->hour(8)->minute(0)->second(0);
        $timeslots = TimeslotCollection::create(Timeslot::create($opening_time), 12);

        $patronCategories = PatronCategory::active()
        ->with(['logbookEntries' => function ($query) use ($timeslots) {
            $query->within($timeslots->start(), $timeslots->end());
        }])->get();

        $formContent = $this->buildFormContent($timeslots, $patronCategories);

        return view('logbook.update', compact(
            'timeslots',
            'patronCategories',
            'formContent',
            'previousDay',
            'nextDay'
        ));
    }

    /**
     * Browse the Year tab containing data for the year(s) selected.
     *
     * @param  Request $request
     *
     * @return Response
     */
    public function browseYear(Request $request)
    {
        $yearsAvailable = LogbookEntry::selectRaw('YEAR(visited_at) as year')
        ->distinct()
        ->pluck('year')
        ->sort();

        return view('logbook.tabs.year', compact(
            'year',
            'yearsAvailable',
            'visitsByYear',
            'visitsByPatronCategory'
        ));
    }

    /**
     * Build the content of the logbook form with the timeslots and patron categories passed.
     * Only existing visits count values are added to the array, which is structured as follows:
     * ['timeslot_no' => ['patron_category_id' => 'visits']];
     *
     * @param  TimeslotCollection $timeslots
     * @param  PatronCategory $categories
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
