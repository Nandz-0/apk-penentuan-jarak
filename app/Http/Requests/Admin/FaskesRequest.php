<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class FaskesRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'nama' => ['required', 'string', 'max:150'],
            'kategori_faskes_id' => ['required', 'integer', 'exists:kategori_faskes,id'],
            'alamat' => ['required', 'string'],
            'latitude' => ['required', 'numeric', 'between:-90,90'],
            'longitude' => ['required', 'numeric', 'between:-180,180'],
            'telepon' => ['nullable', 'string', 'max:30'],
            'jam_operasional' => ['nullable', 'string', 'max:100'],
            'deskripsi' => ['nullable', 'string'],
            'wilayah_id' => ['nullable', 'integer', 'exists:wilayah,id'],
            'node_id' => ['nullable', 'integer', 'exists:nodes,id'],
        ];
    }

    public function attributes(): array
    {
        return [
            'kategori_faskes_id' => 'jenis faskes',
            'jam_operasional' => 'jam operasional',
        ];
    }

    public function messages(): array
    {
        return [
            'nama.required' => 'Nama faskes wajib diisi.',
            'kategori_faskes_id.required' => 'Jenis faskes wajib dipilih.',
            'kategori_faskes_id.exists' => 'Jenis faskes yang dipilih tidak valid.',
            'alamat.required' => 'Alamat wajib diisi.',
            'latitude.required' => 'Latitude wajib diisi.',
            'latitude.numeric' => 'Latitude harus berupa angka.',
            'longitude.required' => 'Longitude wajib diisi.',
            'longitude.numeric' => 'Longitude harus berupa angka.',
        ];
    }
}
