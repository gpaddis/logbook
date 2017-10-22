<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class PatronCategoryRequest extends FormRequest
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
        'name' => 'required|string|max:25|unique:patron_categories,name',
        'abbreviation' => 'string|max:10|nullable|unique:patron_categories,abbreviation',
        'is_active' => 'boolean',
        'is_primary' => 'boolean'
        ];
    }
}
