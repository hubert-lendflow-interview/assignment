<?php

namespace App\Http\Requests;

use App\Rules\DividesBy;
use App\Rules\IsbnCollection;
use Illuminate\Foundation\Http\FormRequest;

class GetBestsellersRequest extends FormRequest
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
     * @return array<string, mixed>
     */
    public function rules()
    {
        return [
            'author' => 'string',
            'isbn' => [
                'bail',
                'array',
                new IsbnCollection,
            ],
            'title' => 'string',
            'offset' => [
                'bail',
                'numeric',
                'integer',
                'min:0',
                new DividesBy(config('http_clients.nytimes.offset_divisor')),
            ],
        ];
    }
}
