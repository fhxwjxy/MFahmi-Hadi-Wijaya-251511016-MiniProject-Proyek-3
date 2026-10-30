<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class LaporanRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        $laporan = $this->route('laporan');

        return $laporan ? $this->user()->can('update', $laporan) : true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'desa_kelurahan_id' => ['required', 'exists:desa_kelurahan,id'],
            'alamat_spesifik' => ['required', 'string', 'max:255'],
            'latitude' => ['required', 'numeric', 'between:-11,6'],
            'longitude' => ['required', 'numeric', 'between:95,141'],
            'deskripsi' => ['required', 'string', 'min:10', 'max:2000'],
            'tingkat_kondisi' => ['required', 'in:ringan,sedang,berat'],
            'waktu_kejadian' => ['required', 'date', 'before_or_equal:now'],
            'bukti' => ['nullable', 'file', 'mimes:jpg,jpeg,png,pdf', 'max:2048'],
        ];
    }

    public function messages(): array
    {
        return [
            'latitude.between' => 'Latitude harus berada di wilayah indonesia (-11 sampai 6)',
            'longtitude.between' => 'Longtitude harus berada di wilayah Indonesia (95 sampai 141)',
            'waktu_kejadian.before_or_equal' => 'Waktu kejadian tidak boleh masa depan/belum terjadi',
            'bukti.mimes' => 'Bukti harus file JPG, JPEG, PNG, atau PDF.',
            'bukti.max' => 'Ukuran bukti maksimal 2 MB.',
        ];
    }
}
