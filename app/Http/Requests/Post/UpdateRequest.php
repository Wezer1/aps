<?php

namespace App\Http\Requests\Post;

use Illuminate\Foundation\Http\FormRequest;

class UpdateRequest extends FormRequest
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
            'title'=>'required|string',
            'image'=>'required|image|mimes:jpeg,png,jpg,gif|max:2048',
            'content'=>'nullable|string',
            'image_ids_for_delete'=>'nullable|array',
            'image_urls_for_delete'=>'nullable|array',
        ];
    }
}
