<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class PostBookRequest extends FormRequest
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
            // @TODO implement
            'isbn' => 'required|regex:/^[0-9]+$/|unique:books|min:13|max:13',
            'title' => 'required|string',
            'description' => 'required|string',
            'authors' => 'required|array',
            'authors.*' => 'required|integer|exists:authors,id',
            'published_year' => 'required|numeric|min:1990|max:2020'
        ];
    }
}
