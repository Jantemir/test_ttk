<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class BookRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'id' => 'nullable|integer|min:0',
            'name' => 'required|string|max:150',
            'year' => 'required|integer|min:0|max:' . date('Y'),
            'description' => 'required|string|max:500',
            'sections' => 'required|array|min:1',
            'authors' => 'required|array|min:1',
            'sections.*.id' => 'exists:sections,id',
            'authors.*.id' => 'exists:authors,id',
            'cover' => 'sometimes|required|mimes:jpeg,png,jpg|max:500',
            'active' => 'sometimes|required|boolean'
        ];
    }
}
