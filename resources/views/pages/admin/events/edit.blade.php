@extends('layouts.admin_layouts')

@section('title', 'Edit Event')

@section('content')

<div class="flex justify-between items-center mb-6">
    <h2 class="text-2xl font-bold">Edit Event</h2>

    <a href="{{ route('admin.events.index') }}" class="btn btn-outline">
        ← Kembali
    </a>
</div>

@if($hasSales)
<div class="alert alert-warning mb-5">
    <span>
        ⚠️ Event ini sudah memiliki penjualan tiket.
        Beberapa field tidak dapat diubah.
    </span>
</div>
@endif

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
    action="{{ route('admin.events.update',$event) }}"
    method="POST"
    enctype="multipart/form-data">

    @csrf
    @method('PUT')

    <div class="card bg-base-100 shadow">

        <div class="card-body">

      <div class="grid md:grid-cols-2 gap-6">

    {{-- Judul Event --}}
    <div class="space-y-2">
        <label class="font-medium">
            Judul Event <span class="text-error">*</span>
        </label>

        <input
            type="text"
            name="judul"
            value="{{ old('judul', $event->judul) }}"
            class="input input-bordered w-full"
            required>
    </div>

    {{-- Kategori --}}
    <div class="space-y-2">
        <label class="font-medium">
            Kategori <span class="text-error">*</span>
        </label>

        <select
            name="kategori_id"
            class="select select-bordered w-full"
            required>

            <option value="">-- Pilih Kategori --</option>

            @foreach($kategoris as $kategori)
                <option
                    value="{{ $kategori->id }}"
                    @selected(old('kategori_id', $event->kategori_id) == $kategori->id)>
                    {{ $kategori->nama }}
                </option>
            @endforeach

        </select>
    </div>

    {{-- Lokasi --}}
    <div class="space-y-2">
        <label class="font-medium">
            Lokasi <span class="text-error">*</span>
        </label>

        <select
            name="lokasi"
            class="select select-bordered w-full"
            required>

            <option value="">-- Pilih Lokasi --</option>

            @foreach($lokasis as $lokasi)
                <option
                    value="{{ $lokasi->nama_lokasi }}"
                    @selected(old('lokasi', $event->lokasi) == $lokasi->nama_lokasi)>
                    {{ $lokasi->nama_lokasi }}
                </option>
            @endforeach

        </select>
    </div>

    {{-- Tanggal & Waktu --}}
    <div class="space-y-2">
        <label class="font-medium">
            Tanggal & Waktu
            <span class="text-error">*</span>

            @if($hasSales)
                <span class="text-warning text-sm">(Tidak dapat diubah)</span>
            @endif
        </label>

        <input
            type="datetime-local"
            name="tanggal_waktu"
            value="{{ old('tanggal_waktu', $event->tanggal_waktu->format('Y-m-d\TH:i')) }}"
            class="input input-bordered w-full"
            @readonly($hasSales)
            required>
    </div>

    {{-- Gambar --}}
    <div class="space-y-2">
        <label class="font-medium">
            Gambar Saat Ini
        </label>

        <img
            src="{{ $event->image_url }}"
            class="rounded-lg shadow w-64 mb-3">

        <input
            type="file"
            id="gambar"
            name="gambar"
            class="file-input file-input-bordered w-full"
            accept="image/*">

        <small class="text-gray-500">
            Kosongkan jika tidak ingin mengubah gambar.
        </small>
    </div>

    {{-- Preview Gambar --}}
    <div class="space-y-2">
        <label class="font-medium">
            Preview
        </label>

        <img
            id="preview"
            class="hidden rounded-lg shadow max-h-60">
    </div>

    {{-- Deskripsi --}}
    <div class="md:col-span-2 space-y-2">
        <label class="font-medium">
            Deskripsi <span class="text-error">*</span>
        </label>

        <textarea
            name="deskripsi"
            rows="5"
            class="textarea textarea-bordered w-full"
            required>{{ old('deskripsi', $event->deskripsi) }}</textarea>
    </div>

</div>

</div>

<hr class="my-8">

<h3 class="text-xl font-bold mb-4">
    Daftar Tiket
</h3>

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

    <button class="btn btn-primary">
        Simpan Event
    </button>

</div>

</div>

</div>

</form>

@endsection

@push('scripts')
<script>

const hasSales = @json($hasSales);

let ticketIndex = 0;

function renderTicket(data = {}) {

    ticketIndex++;

    document.getElementById('ticket-container').insertAdjacentHTML('beforeend', `
        <div class="card bg-base-200 mb-4 ticket-card">

            <div class="card-body">

                <input
                    type="hidden"
                    name="tikets[${ticketIndex}][id]"
                    value="${data.id ?? ''}">

                <div class="flex justify-between items-center">

                    <h3 class="font-bold">
                        Tiket #${ticketIndex}
                    </h3>

                    ${
                        hasSales
                        ? `<span class="badge badge-success">Sudah Terjual</span>`
                        : `<button
                                type="button"
                                class="btn btn-error btn-sm"
                                onclick="this.closest('.ticket-card').remove()">
                                Hapus
                           </button>`
                    }

                </div>

                <div class="grid md:grid-cols-3 gap-4 mt-4">

                    <div>

                        <label class="font-medium">
                            Tipe
                        </label>

                        <select
                            name="tikets[${ticketIndex}][tipe]"
                            class="select select-bordered w-full">

                            <option
                                value="reguler"
                                ${data.tipe == 'reguler' ? 'selected' : ''}>
                                Reguler
                            </option>

                            <option
                                value="premium"
                                ${data.tipe == 'premium' ? 'selected' : ''}>
                                Premium
                            </option>

                        </select>

                    </div>

                    <div>

                        <label class="font-medium">
                            Harga
                        </label>

                        <input
                            type="number"
                            name="tikets[${ticketIndex}][harga]"
                            class="input input-bordered w-full"
                            min="0"
                            value="${data.harga ?? ''}">

                    </div>

                    <div>

                        <label class="font-medium">
                            Stok
                        </label>

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

const existingTickets = @json($event->tikets);

if(existingTickets.length > 0){

    existingTickets.forEach(ticket => {
        renderTicket(ticket);
    });

}else{

    renderTicket();

}

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