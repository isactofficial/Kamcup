<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreFeedRequest extends FormRequest
{
    public function authorize()
    {
        return auth()->check() && auth()->user()->isAdmin();
    }

    public function rules()
    {
        return [
            'title' => 'nullable|string|max:255',
            'content' => 'nullable|string|max:5000',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ];
    }

    public function messages()
    {
        return [
            'image.image' => 'File harus berupa gambar.',
            'image.mimes' => 'Gambar harus JPEG, PNG, JPG, atau GIF.',
            'image.max' => 'Ukuran gambar maksimal 2MB.',
        ];
    }
}
