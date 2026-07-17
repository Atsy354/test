<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class EventFormRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return auth()->check() && auth()->user()->role === 'admin';
    }

    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        return [
            // Event
            'judul' => 'required|string|max:255',
            'deskripsi' => 'required|string',
            'lokasi' => 'required|string|max:255',
            'kategori_id' => 'required|exists:kategoris,id',
            'tanggal_waktu' => 'required|date|after:now',
            'gambar' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',

            // Tikets
            'tikets' => 'required|array|min:1',
            'tikets.*.tipe' => 'required|in:reguler,premium',
            'tikets.*.harga' => 'required|numeric|min:0',
            'tikets.*.stok' => 'required|integer|min:0',
            'tikets.*.id' => 'nullable|exists:tikets,id',
        ];
    }

    /**
     * Custom validation messages.
     */
    public function messages(): array
    {
        return [
            // Event
            'judul.required' => 'Judul event wajib diisi.',
            'judul.string' => 'Judul event harus berupa teks.',
            'judul.max' => 'Judul event maksimal 255 karakter.',

            'deskripsi.required' => 'Deskripsi wajib diisi.',
            'deskripsi.string' => 'Deskripsi harus berupa teks.',

            'lokasi.required' => 'Lokasi wajib diisi.',
            'lokasi.string' => 'Lokasi harus berupa teks.',
            'lokasi.max' => 'Lokasi maksimal 255 karakter.',

            'kategori_id.required' => 'Kategori wajib dipilih.',
            'kategori_id.exists' => 'Kategori tidak valid.',

            'tanggal_waktu.required' => 'Tanggal dan waktu wajib diisi.',
            'tanggal_waktu.date' => 'Format tanggal tidak valid.',
            'tanggal_waktu.after' => 'Tanggal event harus setelah waktu sekarang.',

            'gambar.image' => 'File harus berupa gambar.',
            'gambar.mimes' => 'Format gambar harus JPG, JPEG, atau PNG.',
            'gambar.max' => 'Ukuran gambar maksimal 2 MB.',

            // Tikets
            'tikets.required' => 'Minimal harus ada satu jenis tiket.',
            'tikets.array' => 'Data tiket tidak valid.',
            'tikets.min' => 'Minimal harus ada satu jenis tiket.',

            'tikets.*.tipe.required' => 'Tipe tiket wajib diisi.',
            'tikets.*.tipe.in' => 'Tipe tiket harus reguler atau premium.',

            'tikets.*.harga.required' => 'Harga tiket wajib diisi.',
            'tikets.*.harga.numeric' => 'Harga tiket harus berupa angka.',
            'tikets.*.harga.min' => 'Harga tiket tidak boleh kurang dari 0.',

            'tikets.*.stok.required' => 'Stok tiket wajib diisi.',
            'tikets.*.stok.integer' => 'Stok tiket harus berupa bilangan bulat.',
            'tikets.*.stok.min' => 'Stok tiket tidak boleh kurang dari 0.',

            'tikets.*.id.exists' => 'Data tiket tidak ditemukan.',
        ];
    }
}