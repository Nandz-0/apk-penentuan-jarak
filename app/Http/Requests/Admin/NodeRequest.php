<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class NodeRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'nama' => ['required', 'string', 'max:150'],
            'latitude' => ['required', 'numeric', 'between:-90,90'],
            'longitude' => ['required', 'numeric', 'between:-180,180'],
            'kode' => ['nullable', 'string', 'max:20'],
            'tipe' => ['required', 'in:faskes,jalan'],
            'faskes_id' => ['nullable', 'integer', 'exists:faskes,id'],
            'wilayah_id' => ['nullable', 'integer', 'exists:wilayah,id'],
        ];
    }

    public function attributes(): array
    {
        return [
            'faskes_id' => 'faskes induk',
            'wilayah_id' => 'wilayah',
        ];
    }

    public function messages(): array
    {
        return [
            'nama.required' => 'Nama node wajib diisi.',
            'latitude.required' => 'Latitude wajib diisi.',
            'latitude.numeric' => 'Latitude harus berupa angka.',
            'longitude.required' => 'Longitude wajib diisi.',
            'longitude.numeric' => 'Longitude harus berupa angka.',
            'tipe.required' => 'Tipe node wajib dipilih.',
            'tipe.in' => 'Tipe node harus salah satu dari: faskes atau jalan.',
        ];
    }
}
