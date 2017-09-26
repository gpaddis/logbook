<?php

namespace App\Repositories;

use Carbon\Carbon;
use App\LogbookEntry;

class LogbookEntries
{
    protected $entries;

    /**
     * LogbookEntries constructor.
     *
     * @param LogbookEntry $entries
     */
    public function __construct(LogbookEntry $entries)
    {
        $this->entries = $entries;
    }

    /**
     * Return today's logbook entries.
     *
     * @return Illuminate\Database\Eloquent\Collection
     */
    public function today()
    {
        return $this->entries
        ->whereDate('visited_at', '>=', Carbon::now()->startOfDay())
        ->latest('visited_at')
        ->get();
    }

    /**
     * Return yesterday's logbook entries.
     *
     * @return Illuminate\Database\Eloquent\Collection
     */
    public function yesterday()
    {
        return $this->entries
        ->within(Carbon::now()->subDay()->startOfDay(), Carbon::now()->subDay()->endOfDay())
        ->latest('visited_at')
        ->get();
    }

    /**
     * Return this week's logbook entries.
     *
     * @return Illuminate\Database\Eloquent\Collection
     */
    public function thisWeek()
    {
        return $this->entries
        ->whereDate('visited_at', '>=', Carbon::now()->startOfWeek())
        ->latest('visited_at')
        ->get();
    }

    /**
     * Return last week's logbook entries.
     *
     * @return Illuminate\Database\Eloquent\Collection
     */
    public function lastWeek()
    {
        return $this->entries
        ->within(Carbon::now()->subWeek()->startOfWeek(), Carbon::now()->subWeek()->endOfWeek())
        ->latest('visited_at')
        ->get();
    }
}
