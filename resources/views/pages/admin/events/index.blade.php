@extends('layouts.admin_layouts')

@section('title', 'Manajemen Event')

@section('content')

<div class="flex justify-between items-center mb-6">
    <h2 class="text-2xl font-bold">Manajemen Event</h2>

    <a href="{{ route('admin.events.create') }}" class="btn btn-primary">
        + Tambah Event
    </a>
</div>

{{-- Alert --}}
@if(session('success'))
<div class="alert alert-success mb-4">
    {{ session('success') }}
</div>
@endif

@if(session('error'))
<div class="alert alert-error mb-4">
    {{ session('error') }}
</div>
@endif

{{-- Filter --}}
<div class="card bg-base-100 shadow mb-6">
    <div class="card-body">

        <form method="GET" action="{{ route('admin.events.index') }}">

            <div class="grid md:grid-cols-4 gap-4">

                <div>
                    <label class="label">
                        <span class="label-text">Cari Event</span>
                    </label>

                    <input
                        type="text"
                        name="search"
                        value="{{ request('search') }}"
                        placeholder="Judul / Lokasi"
                        class="input input-bordered w-full">
                </div>

                <div>
                    <label class="label">
                        <span class="label-text">Kategori</span>
                    </label>

                    <select
                        name="kategori_id"
                        class="select select-bordered w-full">

                        <option value="">Semua</option>

                        @foreach($kategoris as $kategori)
                            <option
                                value="{{ $kategori->id }}"
                                @selected(request('kategori_id') == $kategori->id)>
                                {{ $kategori->nama }}
                            </option>
                        @endforeach

                    </select>
                </div>

                <div>
                    <label class="label">
                        <span class="label-text">Sort</span>
                    </label>

                    <select
                        name="sort"
                        class="select select-bordered w-full">

                        <option value="asc" @selected(request('sort')=='asc')>
                            Terlama
                        </option>

                        <option value="desc" @selected(request('sort')=='desc')>
                            Terbaru
                        </option>

                    </select>
                </div>

                <div class="flex items-end gap-2">

                    <button class="btn btn-primary w-full">
                        Filter
                    </button>
                </div>

            </div>

        </form>

    </div>
</div>

{{-- Table --}}
<div class="overflow-x-auto bg-white shadow rounded-lg">

    <table class="table">

        <thead>

            <tr>
                <th>Gambar</th>
                <th>Judul</th>
                <th>Kategori</th>
                <th>Tanggal</th>
                <th>Lokasi</th>
                <th>Status</th>
                <th width="220">Aksi</th>
            </tr>

        </thead>

        <tbody>

        @forelse($events as $event)

            <tr>

                <td>
                    <img
                        src="{{ $event->image_url }}"
                        class="w-16 h-16 rounded object-cover">
                </td>

                <td>
                    {{ $event->judul }}
                </td>

                <td>
                    {{ $event->kategori->nama }}
                </td>

                <td>
                    {{ $event->tanggal_waktu->format('d M Y H:i') }}
                </td>

                <td>
                    {{ $event->lokasi }}
                </td>

                <td>

                    @if($event->status == 'Upcoming')
                        <div class="badge badge-info">
                            Upcoming
                        </div>

                    @elseif($event->status == 'Ongoing')
                        <div class="badge badge-warning">
                            Ongoing
                        </div>

                    @else
                        <div class="badge badge-success">
                            Completed
                        </div>
                    @endif

                </td>

                <td>

                    <div class="flex gap-2">

                        <a
                            href="{{ route('events.show',$event) }}"
                            class="btn btn-sm btn-info">
                            View
                        </a>

                        <a
                            href="{{ route('admin.events.edit',$event) }}"
                            class="btn btn-sm btn-warning">
                            Edit
                        </a>

                        <form
                            action="{{ route('admin.events.destroy',$event) }}"
                            method="POST"
                            onsubmit="return confirm('Yakin ingin menghapus event ini?')">

                            @csrf
                            @method('DELETE')

                            <button class="btn btn-sm btn-error">
                                Delete
                            </button>

                        </form>

                    </div>

                </td>

            </tr>

        @empty

            <tr>

                <td colspan="7" class="text-center py-8">
                    Tidak ada data event.
                </td>

            </tr>

        @endforelse

        </tbody>

    </table>

</div>

<div class="mt-6">
    {{ $events->appends(request()->except('page'))->links() }}
</div>

@endsection