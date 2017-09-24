<?php

namespace App\Http\Requests;

use Carbon\Carbon;
use App\LogbookEntry;
use Illuminate\Foundation\Http\FormRequest;

class LogbookUpdateForm extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'entry.*.start_time' => 'required|date|before_or_equal:' . Carbon::now()->toDateTimeString(),
            'entry.*.end_time' => 'required|date|after:start_time',
            'entry.*.patron_category_id' => 'required|exists:patron_categories,id',
            'entry.*.visits' => 'nullable|integer|min:0',
        ];
    }

    /**
     * Get the error messages for the defined validation rules.
     *
     * @return array
     */
    public function messages()
    {
        return [
            'before_or_equal' => 'You cannot save a logbook entry in the future.'
        ];
    }

    /**
     * Configure the validator instance.
     *
     * @param  \Illuminate\Validation\Validator  $validator
     * @return void
     */
    public function withValidator($validator)
    {
        $validator->after(function ($validator) {
            if ($this->wasFilled() === false) {
                $validator->errors()->add('empty-form', 'You cannot submit an empty form.');
            }
        });
    }

    /**
     * Check if at least one value was typed in.
     *
     * @return bool
     */
    public function wasFilled()
    {
        $fieldsFilled = 0;

        foreach ($this->input('entry.*') as $entry) {
            $entry['visits'] === null ?: $fieldsFilled++;
        }

        return (bool) $fieldsFilled;
    }

    /**
     * Persist non-empty fields in the database, preserving existing live records where possible.
     *
     * @return void
     */
    public function persist()
    {
        foreach ($this->input('entry.*') as $entry) {
            $storedEntries = LogbookEntry::within($entry['start_time'], $entry['end_time'])
            ->where('patron_category_id', $entry['patron_category_id']);

            $count = $storedEntries->count();

            // If there are no changes, do nothing.
            if ($entry['visits'] == $count) {
                return;
            }

            if ($entry['visits'] > $count) {
                $difference = $entry['visits'] - $count;
                $this->addEntries($entry, $difference);
            }

            if ($entry['visits'] < $count) {
                $difference = $count - $entry['visits'];
                $this->deleteEntries($entry, $difference);
            }
        }
    }

    /**
     * Create a $number of records for a given patron category within a time range.
     *
     * @param array  $entry
     * @param int    $number
     *
     * @return void
     */
    protected function addEntries(array $entry, int $number)
    {
        for ($i = 0; $i < $number; $i++) {
            LogbookEntry::create([
                'patron_category_id' => $entry['patron_category_id'],
                'visited_at' => $entry['start_time']
            ]);
        }
    }

    /**
     * Delete a $number of records for a given patron categories within a time range.
     *
     * @param  array  $entry
     * @param  int    $number
     *
     * @return void
     */
    protected function deleteEntries(array $entry, int $number = 1)
    {
        $entries = LogbookEntry::within($entry['start_time'], $entry['end_time'])
        ->where('patron_category_id', $entry['patron_category_id'])
        ->orderBy('visited_at', 'desc');

        for ($i=0; $i < $number; $i++) {
            $entries->first()->delete();
        }
    }
}
