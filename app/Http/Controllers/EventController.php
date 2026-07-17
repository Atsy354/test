<?php

namespace App\Http\Controllers;

use App\Http\Requests\EventFormRequest;
use App\Models\Lokasi;
use App\Models\Event;
use App\Models\Kategori;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class EventController extends Controller
{
    public function index(Request $request)
    {
        $query = Event::with(['kategori', 'tikets']);

        // Filter kategori
        if ($request->filled('kategori_id')) {
            $query->where('kategori_id', $request->kategori_id);
        }

        // Search judul / lokasi
        if ($request->filled('search')) {
            $search = $request->search;

            $query->where(function ($q) use ($search) {
                $q->where('judul', 'like', "%{$search}%")
                ->orWhere('lokasi', 'like', "%{$search}%");
            });
        }

        // Sort
        $sort = $request->get('sort', 'asc');
        $query->orderBy('tanggal_waktu', $sort);

        $events = $query->paginate(4);

        $kategoris = Kategori::all();

        return view('pages.admin.events.index', compact(
            'events',
            'kategoris'
        ));
    }

    public function create()
    {
        $kategoris = Kategori::all();
        $lokasis = Lokasi::where('aktif', 'Y')->get();

        return view('pages.admin.events.create', compact('kategoris', 'lokasis'));
    }

    public function store(EventFormRequest $request)
    {
        // Handle upload gambar
        if ($request->hasFile('gambar')) {
            $gambar = $request->file('gambar')->store('events', 'public');
        } else {
            $gambar = 'konser.jpg';
        }

        // Simpan event
        $event = Event::create([
            'user_id' => auth()->id(),
            'kategori_id' => $request->kategori_id,
            'lokasi' => $request->lokasi,
            'judul' => $request->judul,
            'deskripsi' => $request->deskripsi,
            'gambar' => $gambar,
            'tanggal_waktu' => $request->tanggal_waktu,
        ]);
        // Simpan tiket
        foreach ($request->tikets as $tiket) {
            $event->tikets()->create([
                'tipe' => $tiket['tipe'],
                'harga' => $tiket['harga'],
                'stok' => $tiket['stok'],
            ]);
        }

        return redirect()
            ->route('admin.events.index')
            ->with('success', 'Event berhasil ditambahkan.');
    }

    public function edit(Event $event)
    {
        $kategoris = Kategori::all();
        $lokasis = Lokasi::where('aktif', 'Y')->get();

        $hasSales = $event->hasSales();

        return view(
            'pages.admin.events.edit',
            compact(
                'event',
                'kategoris',
                'lokasis',
                'hasSales'
            )
        );
    }

    public function update(EventFormRequest $request, Event $event)
    {
        // Jika sudah ada penjualan, tanggal tidak boleh diubah
        if (
            $event->hasSales() &&
            $event->tanggal_waktu->format('Y-m-d H:i:s') !==
            date('Y-m-d H:i:s', strtotime($request->tanggal_waktu))
        ) {
            return back()
                ->withInput()
                ->with('error', 'Tanggal event tidak dapat diubah karena event sudah memiliki penjualan.');
        }

        // Handle upload gambar
        $gambar = $event->gambar;

        if ($request->hasFile('gambar')) {

            // Hapus gambar lama jika bukan gambar default
            if (
                $event->gambar &&
                $event->gambar !== 'konser.jpg' &&
                Storage::disk('public')->exists($event->gambar)
            ) {
                Storage::disk('public')->delete($event->gambar);
            }

            $gambar = $request->file('gambar')->store('events', 'public');
        }

        // Update event
        $event->update([
            'kategori_id'   => $request->kategori_id,
            'lokasi'        => $request->lokasi,
            'judul'         => $request->judul,
            'deskripsi'     => $request->deskripsi,
            'gambar'        => $gambar,
            'tanggal_waktu' => $request->tanggal_waktu,
        ]);

        // ID tiket yang masih dipakai
        $existingIds = [];

        foreach ($request->tikets as $tiket) {

            // Update tiket lama
            if (!empty($tiket['id'])) {

                $ticketModel = $event->tikets()->find($tiket['id']);

                if ($ticketModel) {

                    $ticketModel->update([
                        'tipe' => $tiket['tipe'],
                        'harga' => $tiket['harga'],
                        'stok' => $tiket['stok'],
                    ]);

                    $existingIds[] = $ticketModel->id;
                }

            } else {

                // Tambah tiket baru
                $newTicket = $event->tikets()->create([
                    'tipe' => $tiket['tipe'],
                    'harga' => $tiket['harga'],
                    'stok' => $tiket['stok'],
                ]);

                $existingIds[] = $newTicket->id;
            }
        }

        // Jika belum ada penjualan, tiket yang dihapus dari form ikut dihapus
        if (!$event->hasSales()) {
            $event->tikets()
                ->whereNotIn('id', $existingIds)
                ->delete();
        }

        return redirect()
            ->route('admin.events.index')
            ->with('success', 'Event berhasil diperbarui.');
    }

    public function destroy(Event $event)
    {
        // Tidak boleh dihapus jika sudah ada penjualan
        if ($event->hasSales()) {
            return redirect()
                ->route('admin.events.index')
                ->with('error', 'Event tidak dapat dihapus karena sudah memiliki penjualan.');
        }

        // Hapus gambar jika bukan default
        if (
            $event->gambar &&
            $event->gambar !== 'konser.jpg' &&
            Storage::disk('public')->exists($event->gambar)
        ) {
            Storage::disk('public')->delete($event->gambar);
        }

        $event->delete();

        return redirect()
            ->route('admin.events.index')
            ->with('success', 'Event berhasil dihapus.');
    }

    public function show(Event $event)
    {
        $event->load([
            'kategori',
            'tikets',
        ]);

        $relatedEvents = Event::with(['kategori', 'tikets'])
            ->where('kategori_id', $event->kategori_id)
            ->where('id', '!=', $event->id)
            ->where('tanggal_waktu', '>', now())
            ->orderBy('tanggal_waktu')
            ->take(4)
            ->get();
            
        return view('events.show', compact(
            'event',
            'relatedEvents'
        ));
    }
}