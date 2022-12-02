<?php

declare(strict_types=1);

namespace Canvas\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreTopicRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return request()->user('canvas')->isAdmin;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'name' => 'required',
            'slug' => [
                'required',
                'alpha_dash',
                Rule::unique('canvas_topics')->where(function ($query) {
                    return $query->where('slug', request('slug'))->where('user_id', request()->user('canvas')->id);
                })->ignore(request('id'))->whereNull('deleted_at'),
            ],
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
            'name.required' => trans('canvas::app.name_is_required'),
            'slug.required' => trans('canvas::app.slug_is_required'),
            'slug.alpha_dash' => trans('canvas::app.slug_must_only_contain'),
            'slug.unique' => trans('canvas::app.slug_has_already_been_taken'),
        ];
    }
}
