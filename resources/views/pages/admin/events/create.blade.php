@extends('layouts.admin_layouts')

@section('title', 'Tambah Event')

@section('content')

<div class="flex justify-between items-center mb-6">
    <h2 class="text-2xl font-bold">Tambah Event</h2>

    <a href="{{ route('admin.events.index') }}" class="btn btn-outline">
        ← Kembali
    </a>
</div>

@if ($errors->any())
<div class="alert alert-error mb-4">
    <ul class="list-disc pl-5">
        @foreach ($errors->all() as $error)
            <li>{{ $error }}</li>
        @endforeach
    </ul>
</div>
@endif

<form
    action="{{ route('admin.events.store') }}"
    method="POST"
    enctype="multipart/form-data">

    @csrf

    <div class="card bg-base-100 shadow">

        <div class="card-body">

            <div class="grid md:grid-cols-2 gap-6">
                <div class="space-y-2">
                    <label class="font-medium">
                        Judul Event <span class="text-error">*</span>
                    </label>

                    <input
                        type="text"
                        name="judul"
                        value="{{ old('judul') }}"
                        class="input input-bordered w-full"
                        required>
                </div>
        <div class="space-y-2">
            <label class="font-medium">
                Kategori <span class="text-error">*</span>
            </label>

            <select
                name="kategori_id"
                class="select select-bordered w-full"
                required>

                <option value="">Pilih Kategori</option>

                @foreach($kategoris as $kategori)

                    <option
                        value="{{ $kategori->id }}"
                        @selected(old('kategori_id') == $kategori->id)>
                        {{ $kategori->nama }}
                    </option>

                @endforeach

            </select>

        </div>
        <div class="space-y-2">

            <label class="font-medium">
                Lokasi <span class="text-error">*</span>
            </label>

            <select name="lokasi" class="form-control" required>
                <option value="">-- Pilih Lokasi --</option>

                @foreach($lokasis as $lokasi)
                    <option value="{{ $lokasi->nama_lokasi }}">
                        {{ $lokasi->nama_lokasi }}
                    </option>
                @endforeach
            </select>

        </div>
        <div class="space-y-2">

            <label class="font-medium">
                Tanggal & Waktu
                <span class="text-error">*</span>
            </label>

            <input
                type="datetime-local"
                name="tanggal_waktu"
                value="{{ old('tanggal_waktu') }}"
                class="input input-bordered w-full"
                required>

        </div>
        <div class="space-y-2">

            <label class="font-medium">
                Gambar Event
            </label>

            <input
                type="file"
                id="gambar"
                name="gambar"
                class="file-input file-input-bordered w-full"
                accept="image/*">

        </div>
        <div>

            <img
                id="preview"
                class="hidden rounded-lg shadow max-h-60">

        </div>
        <div class="md:col-span-2 space-y-2">

            <label class="font-medium">
                Deskripsi
                <span class="text-error">*</span>
            </label>

            <textarea
                name="deskripsi"
                rows="5"
                class="textarea textarea-bordered w-full"
                required>{{ old('deskripsi') }}</textarea>

        </div>
        </div>

            <hr class="my-8">

            {{-- Dynamic Ticket akan kita isi nanti --}}

            <div id="ticket-container"></div>

            <div class="mt-4">
                <button
                    type="button"
                    id="btnTambahTicket"
                    class="btn btn-info">
                    + Tambah Tiket
                </button>
            </div>

                    <div class="card-actions justify-end mt-8">

                        <button
                            class="btn btn-primary">

                            Simpan Event

                        </button>

                    </div>

                </div>

            </div>

</form>

@endsection

@push('scripts')
<script>

let ticketIndex = 0;

function renderTicket(data = {}) {

    ticketIndex++;

    document.getElementById('ticket-container').insertAdjacentHTML('beforeend', `
        <div class="card bg-base-200 mb-4 ticket-card">
            <div class="card-body">

                <div class="flex justify-between items-center">
                    <h3 class="font-bold">
                        Tiket #${ticketIndex}
                    </h3>

                    <button
                        type="button"
                        class="btn btn-error btn-sm"
                        onclick="this.closest('.ticket-card').remove()">
                        Hapus
                    </button>
                </div>

                <div class="grid md:grid-cols-3 gap-4 mt-4">

                    <div>
                        <label class="font-medium">Tipe</label>

                        <select
                            name="tikets[${ticketIndex}][tipe]"
                            class="select select-bordered w-full">

                            <option value="reguler">Reguler</option>
                            <option value="premium">Premium</option>

                        </select>
                    </div>

                    <div>
                        <label class="font-medium">Harga</label>

                        <input
                            type="number"
                            name="tikets[${ticketIndex}][harga]"
                            class="input input-bordered w-full"
                            min="0"
                            value="${data.harga ?? ''}">
                    </div>

                    <div>
                        <label class="font-medium">Stok</label>

                        <input
                            type="number"
                            name="tikets[${ticketIndex}][stok]"
                            class="input input-bordered w-full"
                            min="0"
                            value="${data.stok ?? ''}">
                    </div>

                </div>

            </div>
        </div>
    `);

}

document
.getElementById('btnTambahTicket')
.addEventListener('click', () => renderTicket());

renderTicket();

document
.getElementById('gambar')
.addEventListener('change', function(e){

    const file = e.target.files[0];

    if(!file) return;

    const preview = document.getElementById('preview');

    preview.src = URL.createObjectURL(file);

    preview.classList.remove('hidden');

});

</script>
@endpush