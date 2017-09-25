<?php

namespace App\Http\Requests;

use Carbon\Carbon;
use App\LogbookEntry;
use Illuminate\Validation\Validator;
use Illuminate\Database\Eloquent\Builder;
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
     * @param  Validator  $validator
     * @return void
     */
    public function withValidator(Validator $validator)
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
        foreach ($this->input('entry.*') as $formData) {
            $storedEntries = LogbookEntry::within($formData['start_time'], $formData['end_time'])
            ->wherePatronCategoryId($formData['patron_category_id'])
            ->latest('visited_at');

            $count = $storedEntries->count();

            if ($formData['visits'] === $count) {
                continue;
            }

            if ($formData['visits'] === 0) {
                $storedEntries->delete();
                continue;
            }

            if ($formData['visits'] > $count) {
                $difference = $formData['visits'] - $count;
                $this->addEntries($formData, $difference);
                continue;
            }

            if ($formData['visits'] < $count) {
                $difference = $count - $formData['visits'];
                $this->deleteEntries($storedEntries, $difference);
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
     * @param  Builder  $storedEntries
     * @param  int      $number
     *
     * @return void
     */
    protected function deleteEntries(Builder $storedEntries, int $number = 1)
    {
        for ($i=0; $i < $number; $i++) {
            $storedEntries->first()->delete();
        }
    }
}
