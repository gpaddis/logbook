<?php

namespace App\Http\Requests;

use App\Logbook\Entry;
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
        'entry.*.start_time' => 'required|date|before:' . \Carbon\Carbon::tomorrow()->toDateString(),
        'entry.*.end_time' => 'required|date|after:entry.*.start_time',
        'entry.*.patron_category_id' => 'required|exists:patron_categories,id',
        'entry.*.count' => 'nullable|integer|min:1'
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
        'min' => 'The count must be a positive number. Correct the fields in red and try again.',
        'before' => 'You cannot save a logbook entry for the future.'
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
            if ($this->filled() === false) {
                $validator->errors()->add('empty-form', 'You cannot submit an empty form.');
            }
        });
    }

    /**
     * Check if at least one value was typed in.
     * 
     * @return bool
     */
    public function filled()
    {
        $fieldsFilled = 0;  

        dd($this->input('entry.*'));
        
        foreach ($this->input('entry.*') as $entry) {
            $entry['count'] === null ?: $fieldsFilled++;
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
        foreach ($this->input('entry.*') as $entry) {
            Entry::updateOrCreateIfNotNull($entry);
        }
    }
}
