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
        'entry.*.visits' => 'integer|min:0',
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
     * Persist non-empty fields in the database.
     *
     * @return void
     */
    public function persist()
    {
        // TODO: refactor this mess
        foreach ($this->input('entry.*') as $entry) {
            if ($entry['visits'] > 0) {
                for ($i = 0; $i < $entry['visits']; $i++) {
                    LogbookEntry::create([
                        'patron_category_id' => $entry['patron_category_id'],
                        'visited_at' => $entry['start_time'],
                        'recorded_at' => Carbon::now()
                        ]);
                }
            }

            if ($entry['visits'] === 0) {
                LogbookEntry::within($entry['start_time'], $entry['end_time'])
                ->delete();
            }
        }
    }
}
