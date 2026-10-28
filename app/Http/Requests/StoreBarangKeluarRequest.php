<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreBarangKeluarRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'barang_id'  => ['required', 'integer', 'exists:barang,id'],
            'jumlah'     => ['required', 'integer', 'min:1'],
            'tanggal'    => ['required', 'date', 'before_or_equal:today'],
            'keperluan'  => ['nullable', 'string', 'max:150'],
            'penerima'   => ['nullable', 'string', 'max:100'],
            'keterangan' => ['nullable', 'string', 'max:500'],
        ];
    }

    public function messages(): array
    {
        return [
            'barang_id.required'      => 'Pilih barang terlebih dahulu.',
            'barang_id.exists'        => 'Barang yang dipilih tidak ditemukan.',
            'jumlah.required'         => 'Jumlah wajib diisi.',
            'jumlah.min'              => 'Jumlah minimal 1.',
            'tanggal.required'        => 'Tanggal wajib diisi.',
            'tanggal.before_or_equal' => 'Tanggal tidak boleh di masa depan.',
        ];
    }
}