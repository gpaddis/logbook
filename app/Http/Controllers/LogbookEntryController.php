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
    protected $entries;

    /**
     * ThreadsController constructor
     */
    public function __construct(LogbookEntries $entries)
    {
        $this->middleware('auth');
        $this->entries = $entries;
    }

    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index()
    {
        $aggregates = LogbookEntry::getAggregatesWithin(Carbon::now()->subWeek()->startOfWeek(), Carbon::now());
// dd($aggregates);
        $today = $aggregates->where('day', Carbon::now()->toDateString());
        $yesterday = $aggregates->where('day', Carbon::now()->subDay()->toDateString());
        $thisWeek = $aggregates->where('day', '>=', Carbon::now()->startOfWeek()->toDateString());
        $lastWeek = $aggregates->where('day', '>=', Carbon::now()->subWeek()->startOfWeek()->toDateString())
            ->where('day', '<=', Carbon::now()->subWeek()->endOfWeek()->toDateString());
// dd($thisWeek);

        return view('logbook.index', [
            'today' => $today,
            'yesterday' => $yesterday,
            'thisWeek' => $thisWeek,
            'lastWeek' => $lastWeek,
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

        return redirect()->route('logbook.index');
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