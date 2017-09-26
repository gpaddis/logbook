<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\LogbookEntry;
use Timeslot\Timeslot;
use App\PatronCategory;
use Illuminate\Http\Request;
use Timeslot\TimeslotCollection;
use App\Http\Requests\LogbookUpdateForm;

class LogbookEntryController extends Controller
{
    /**
     * ThreadsController constructor
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
        $entries = LogbookEntry::whereDate('visited_at', '>=', Carbon::now()->subWeek()->startOfWeek())
        ->latest('visited_at')
        ->get()
        ->groupBy(function ($entry) {
            return $entry->visited_at->toDateString();
        });

        $today = $entries->get(Carbon::now()->toDateString())->count();
        $yesterday = $entries->get(Carbon::now()->subDay()->toDateString())->count();

        return view('logbook.index', compact(
            'today',
            'yesterday'
        ));
    }


    /**
     * Validate and store the visits submitted with the logbook update form.
     *
     * @param  LogbookUpdateForm $form
     *
     * @return Response
     */
    public function store(LogbookUpdateForm $form)
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
