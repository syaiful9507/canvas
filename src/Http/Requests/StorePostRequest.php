<?php

declare(strict_types=1);

namespace Canvas\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StorePostRequest extends FormRequest
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
            'slug' => [
                'required',
                'alpha_dash',
                Rule::unique('canvas_posts')->where(function ($query) {
                    return $query->where('slug', request('slug'))->where('user_id', request()->user('canvas')->id);
                })->ignore(request('id'))->whereNull('deleted_at'),
            ],
            'title' => 'required',
            'summary' => 'nullable|string',
            'body' => 'nullable|string',
            'published_at' => 'nullable|date',
            'featured_image' => 'nullable|string',
            'featured_image_caption' => 'nullable|string',
            'meta' => 'nullable|array',
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
            'slug.required' => trans('canvas::app.slug_is_required'),
            'slug.alpha_dash' => trans('canvas::app.slug_must_only_contain'),
            'slug.unique' => trans('canvas::app.slug_has_already_been_taken'),
            'title.required' => trans('canvas::app.title_is_required'),
            'summary.string' => trans('canvas::app.summary_must_be_a_string'),
            'body.string' => trans('canvas::app.body_must_be_a_string'),
            'published_at.date' => trans('canvas::app.published_at_is_not_a_valid_date'),
            'featured_image.string' => trans('canvas::app.featured_image_must_be_a_string'),
            'featured_image_caption.string' => trans('canvas::app.featured_image_caption_must_be_a_string'),
            'meta.array' => trans('canvas::app.meta_must_be_an_array'),
        ];
    }
}
