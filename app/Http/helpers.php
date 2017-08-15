<?php

/**
 * Generate an id based on timeslot and patron category to identify the entry uniquely.
 * @param  App\Timeslot $timeslot
 * @param  App\PatronCategory $category
 * @return string
 */
function entry_id($timeslot, $category)
{
    return md5($timeslot->start()) . $category->id;
}
