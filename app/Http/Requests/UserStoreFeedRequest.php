<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UserStoreFeedRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return auth()->check();
    }

    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        return [
            'title' => 'required|string|max:255',
            'meet_date' => 'required|date|after:now',
            'meet_location' => 'required|string|max:255',
            'meet_max_people' => 'required|integer|min:2|max:1000',
            'meet_description' => 'required|string|max:2000',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ];
    }

    /**
     * Get custom error messages.
     */
    public function messages(): array
    {
        return [
            'title.required' => 'Judul meets wajib diisi.',
            'meet_date.required' => 'Tanggal meets wajib diisi.',
            'meet_date.after' => 'Tanggal meets harus di masa depan.',
            'meet_location.required' => 'Lokasi meets wajib diisi.',
            'meet_max_people.required' => 'Jumlah orang wajib diisi.',
            'meet_max_people.min' => 'Minimal 2 orang.',
            'meet_description.required' => 'Deskripsi meets wajib diisi.',
            'image.image' => 'File harus berupa gambar.',
            'image.mimes' => 'Gambar harus JPEG, PNG, JPG, atau GIF.',
            'image.max' => 'Ukuran gambar maksimal 2MB.',
        ];
    }
}

