<x-app-layout>
    <div class="max-w-7xl mx-auto py-8 px-6">

        {{-- Event Header --}}
        <div class="card bg-base-100 shadow-xl mb-8">
            <div class="card-body">
                <div class="flex flex-col lg:flex-row gap-8">

                    {{-- Event Image --}}
                    <div class="lg:w-1/2">
                        <img
                            src="{{ $event->image_url }}"
                            alt="{{ $event->judul }}"
                            class="w-full h-96 object-cover rounded-lg shadow-md">
                    </div>

                    {{-- Event Detail --}}
                    <div class="lg:w-1/2">

                        <h1 class="text-4xl font-bold mb-4">
                            {{ $event->judul }}
                        </h1>

                        <div class="badge badge-primary badge-lg mb-4">
                            {{ $event->kategori->nama }}
                        </div>

                        <div class="space-y-4 mb-6">

                            <div class="flex items-center gap-3">
                                <svg class="w-5 h-5 text-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                </svg>

                                <span>
                                    {{ $event->tanggal_waktu->translatedFormat('d F Y, H:i') }}
                                </span>
                            </div>

                            <div class="flex items-center gap-3">
                                <svg class="w-5 h-5 text-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                                </svg>

                                <span>
                                    {{ $event->lokasi }}
                                </span>
                            </div>

                        </div>

                        {{-- Deskripsi --}}
                        <div class="prose max-w-none">
                            <h3 class="text-lg font-semibold mb-2">
                                Deskripsi Event
                            </h3>

                            <p class="text-gray-600">
                                {{ $event->deskripsi }}
                            </p>
                        </div>

                    </div>

                </div>
            </div>
        </div>


        {{-- Ticket --}}
        @if($event->tikets->count())

        <div class="card bg-base-100 shadow-xl">

            <div class="card-body">

                <h2 class="text-2xl font-bold mb-6">
                    Pilih Tiket
                </h2>

                <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-6">

                    @foreach($event->tikets as $tiket)

                    <div class="card bg-base-200">

                        <div class="card-body">

                            <h3 class="card-title">
                                Tiket {{ ucfirst($tiket->tipe) }}
                            </h3>

                            <p class="text-3xl font-bold text-primary">
                                Rp {{ number_format($tiket->harga,0,',','.') }}
                            </p>

                            <div class="flex justify-between items-center">

                                <span class="badge {{ $tiket->stok > 0 ? 'badge-success' : 'badge-error' }}">
                                    {{ $tiket->stok > 0 ? $tiket->stok.' tersedia' : 'Habis' }}
                                </span>

                            </div>

                            <button
                                class="btn btn-primary mt-4 {{ $tiket->stok <= 0 ? 'btn-disabled' : '' }}"
                                {{ $tiket->stok <=0 ? 'disabled' : '' }}>

                                {{ $tiket->stok <=0 ? 'Habis Terjual' : 'Beli Sekarang' }}

                            </button>

                        </div>

                    </div>

                    @endforeach

                </div>

            </div>

        </div>

        @else

        <div class="alert alert-warning mt-8">
            Belum ada tiket tersedia untuk event ini.
        </div>

        @endif


        {{-- Related Events --}}
        @if($relatedEvents->count())

        <section class="mt-16">

            <h2 class="text-3xl font-bold mb-8">
                Event Terkait
            </h2>

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">

                @foreach($relatedEvents as $related)

                <x-event-card
                    :title="$related->judul"
                    :date="$related->tanggal_waktu"
                    :location="$related->lokasi"
                    :price="$related->tikets->min('harga')"
                    :image="$related->image_url"
                    :href="route('events.show', $related)"
                />

                @endforeach

            </div>

        </section>

        @endif


        {{-- Back Button --}}
        <div class="mt-10">

            <a
                href="{{ route('home') }}"
                class="btn btn-outline">

                ← Kembali ke Beranda

            </a>

        </div>

    </div>
</x-app-layout>