@extends('layouts.admin_layouts')

@section('title','Manajemen Lokasi')

@section('content')

<div class="flex justify-between items-center mb-6">

    <h2 class="text-2xl font-bold">
        Manajemen Lokasi
    </h2>

    <a href="{{ route('lokasi.create') }}" class="btn btn-primary">
        + Tambah Lokasi
    </a>

</div>

@if(session('success'))
<div class="alert alert-success mb-4">
    {{ session('success') }}
</div>
@endif

<div class="overflow-x-auto bg-white rounded-lg shadow">

<table class="table">

    <thead>

        <tr>
            <th>No</th>
            <th>Nama Lokasi</th>
            <th>Alamat</th>
            <th width="180">Aksi</th>
        </tr>

    </thead>

    <tbody>

    @forelse($lokasis as $lokasi)

        <tr>

            <td>{{ $loop->iteration }}</td>

            <td>{{ $lokasi->nama_lokasi }}</td>

            <td>{{ $lokasi->alamat }}</td>

            <td>

                <div class="flex gap-2">

                    <a
                        href="{{ route('lokasi.edit',$lokasi) }}"
                        class="btn btn-warning btn-sm">

                        Edit

                    </a>

                    <form
                        action="{{ route('lokasi.destroy',$lokasi) }}"
                        method="POST"
                        onsubmit="return confirm('Yakin?')">

                        @csrf
                        @method('DELETE')

                        <button class="btn btn-error btn-sm">
                            Hapus
                        </button>

                    </form>

                </div>

            </td>

        </tr>

    @empty

        <tr>

            <td colspan="4" class="text-center py-8">

                Belum ada data.

            </td>

        </tr>

    @endforelse

    </tbody>

</table>

</div>

<div class="mt-6">

    {{ $lokasis->links() }}

</div>

@endsection