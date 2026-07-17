@extends('layouts.admin_layouts')

@section('title','Edit Lokasi')

@section('content')

<h2 class="text-2xl font-bold mb-6">
    Edit Lokasi
</h2>

<form
    action="{{ route('lokasi.update',$lokasi) }}"
    method="POST">

    @csrf
    @method('PUT')

    <div class="card bg-base-100 shadow">

        <div class="card-body">

            <div class="space-y-4">

                <div>

                    <label>Nama Lokasi</label>

                    <input
                        type="text"
                        name="nama_lokasi"
                        value="{{ old('nama_lokasi',$lokasi->nama_lokasi) }}"
                        class="input input-bordered w-full"
                        required>

                </div>

                <div>

                    <label>Alamat</label>

                    <textarea
                        name="alamat"
                        rows="4"
                        class="textarea textarea-bordered w-full"
                        required>{{ old('alamat',$lokasi->alamat) }}</textarea>

                </div>

            </div>

            <div class="card-actions justify-end mt-6">

                <button class="btn btn-primary">

                    Update

                </button>

            </div>

        </div>

    </div>

</form>

@endsection