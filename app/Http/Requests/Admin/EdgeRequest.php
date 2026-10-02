<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class EdgeRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'node_awal' => ['required', 'integer', 'exists:nodes,id'],
            'node_tujuan' => ['required', 'integer', 'exists:nodes,id', 'different:node_awal'],
            'jarak' => ['required', 'numeric', 'min:1'],
            'nama_jalan' => ['nullable', 'string', 'max:150'],
        ];
    }

    public function attributes(): array
    {
        return [
            'node_awal' => 'node awal',
            'node_tujuan' => 'node tujuan',
            'nama_jalan' => 'nama jalan',
        ];
    }

    public function messages(): array
    {
        return [
            'node_awal.required' => 'Node awal wajib dipilih.',
            'node_awal.exists' => 'Node awal yang dipilih tidak valid.',
            'node_tujuan.required' => 'Node tujuan wajib dipilih.',
            'node_tujuan.exists' => 'Node tujuan yang dipilih tidak valid.',
            'node_tujuan.different' => 'Node tujuan harus berbeda dengan node awal.',
            'jarak.required' => 'Jarak wajib diisi.',
            'jarak.numeric' => 'Jarak harus berupa angka.',
            'jarak.min' => 'Jarak minimal 1 meter.',
        ];
    }
}
