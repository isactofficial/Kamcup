<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\TournamentHostRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
// use App\Models\User; // You don't need to import User model here unless you directly query it for something

class TournamentHostRequestController extends Controller
{
    /**
     * Menampilkan daftar semua permintaan host (untuk halaman index admin).
     */
    public function index(Request $request)
    {
        $query = TournamentHostRequest::query(); // Ini sudah benar (tidak akan mengambil data soft delete)

        // Filter berdasarkan Status
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // Filter berdasarkan Urutan (Sort)
        $sort = $request->input('sort', 'latest');
        if ($sort == 'latest') {
            $query->orderBy('created_at', 'desc');
        } else {
            $query->orderBy('created_at', 'asc');
        }

        $hostRequests = $query->paginate(10)->withQueryString();

        // --- PERBAIKAN ---
        // Mengarahkan ke view yang benar sesuai saran error Laravel
        // 'host-request.index' (singular) diubah menjadi 'host-requests.index' (plural)
        return view('host-requests.index', compact('hostRequests'));
    }

    /**
     * Menampilkan detail satu permintaan host.
     * * --- PERBAIKAN ---
     * Mengganti $request menjadi $tournamentHostRequest agar sesuai dengan Rute
     */
    public function show(TournamentHostRequest $tournamentHostRequest)
    {
        // --- PERBAIKAN ---
        // Mengarahkan ke view yang benar sesuai saran error Laravel
        // 'host-request.show' (singular) diubah menjadi 'host-requests.show' (plural)
        return view('host-requests.show', ['request' => $tournamentHostRequest]);
    }


    // ===== USER: Tampilkan form request
    public function create()
    {
        // Pastikan view ini ada: resources/views/host_request/create.blade.php
        return view('host_request.create');
    }

    // ===== USER: Simpan permintaan host
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'responsible_name' => 'required|string|max:255',
            'email' => 'required|email|max:2255',
            'phone' => 'required|string|max:20', // Sesuaikan max length jika perlu
            'tournament_title' => 'required|string|max:255',
            'venue_name' => 'required|string|max:255',
            'venue_address' => 'required|string',
            'estimated_capacity' => 'nullable|integer|min:0', // 'nullable' karena opsional
            'proposed_date' => 'required|date',
            'available_facilities' => 'nullable|string', // 'nullable' karena opsional
            'notes' => 'nullable|string', // 'nullable' karena opsional
        ]);

        try {
            TournamentHostRequest::create([
                'user_id' => Auth::id(), // Mengambil ID pengguna yang sedang login
                'status' => 'pending', // Status awal
                'responsible_name' => $validatedData['responsible_name'],
                'email' => $validatedData['email'],
                'phone' => $validatedData['phone'],
                'tournament_title' => $validatedData['tournament_title'],
                'venue_name' => $validatedData['venue_name'],
                'venue_address' => $validatedData['venue_address'],
                'estimated_capacity' => $validatedData['estimated_capacity'],
                'proposed_date' => $validatedData['proposed_date'],
                'available_facilities' => $validatedData['available_facilities'],
                'notes' => $validatedData['notes'],
            ]);

            // Redirect ke halaman sukses (buat halaman ini jika perlu)
            // Anda bisa juga redirect ke dashboard pengguna
            return redirect()->route('dashboard')->with('success', 'Permintaan Anda untuk menjadi host telah berhasil dikirim dan sedang ditinjau.');

        } catch (\Exception $e) {
            Log::error('Gagal menyimpan permintaan host: ' . $e->getMessage());
            // Redirect kembali ke form dengan error
            return back()->withInput()->with('error', 'Terjadi kesalahan saat mengirim permintaan. Silakan coba lagi.');
        }
    }

    /**
     * Menyetujui permintaan host.
     *
     * --- PERBAIKAN ---
     * Mengganti $id menjadi Route Model Binding $tournamentHostRequest
     */
    public function approve(TournamentHostRequest $tournamentHostRequest)
    {
        try {
            if ($tournamentHostRequest->status === 'pending') {
                $tournamentHostRequest->status = 'approved';
                $tournamentHostRequest->rejection_reason = null; // Hapus alasan penolakan jika ada
                $tournamentHostRequest->save();

                // Di sini Anda bisa menambahkan logika untuk:
                // 1. Membuat turnamen baru di tabel 'tournaments' berdasarkan data ini.
                // 2. Mengirim email notifikasi ke host.

                return redirect()->route('admin.host-requests.index')->with('success', 'Permintaan host turnamen telah disetujui.');
            } else {
                return redirect()->route('admin.host-requests.index')->with('error', 'Permintaan tidak dapat disetujui. Status bukan "pending".');
            }
        } catch (\Exception $e) {
            Log::error('Error approving host request (ID: ' . $tournamentHostRequest->id . '): ' . $e->getMessage());
            return redirect()->route('admin.host-requests.index')->with('error', 'Gagal menyetujui permintaan. Mohon coba lagi.');
        }
    }


    /**
     * Menolak request
     *
     * --- PERBAIKAN ---
     * Mengganti $id menjadi Route Model Binding $tournamentHostRequest
     * Tetap menerima Request $request untuk validasi
     */
    public function reject(Request $request, TournamentHostRequest $tournamentHostRequest)
    {
        $validatedData = $request->validate([
            'rejection_reason' => 'required|string|min:10|max:1000', // Alasan penolakan minimal 10 karakter
        ]);

        try {
            if ($tournamentHostRequest->status === 'pending') {
                $tournamentHostRequest->status = 'rejected';
                $tournamentHostRequest->rejection_reason = $validatedData['rejection_reason'];
                $tournamentHostRequest->save();

                // Tambahkan logika lain di sini setelah ditolak, seperti:
                // - Mengirim notifikasi email ke pengguna yang ditolak beserta alasannya.

                return redirect()->route('admin.host-requests.index')->with('success', 'Permintaan tuan rumah turnamen telah ditolak.');
            } else {
                return redirect()->route('admin.host-requests.index')->with('error', 'Permintaan tidak dapat ditolak. Status bukan \"pending\".');
            }
        } catch (\Exception $e) {
            Log::error('Error rejecting tournament host request (ID: ' . $tournamentHostRequest->id . '): ' . $e->getMessage());
            return redirect()->route('admin.host-requests.index')->with('error', 'Gagal menolak permintaan. Mohon coba lagi.');
        }
    }

    /**
     * Menghapus data permintaan host dari database.
     *
     * --- PERBAIKAN ---
     * Mengganti $request menjadi $tournamentHostRequest agar sesuai dengan Rute
     */
    public function destroy(TournamentHostRequest $tournamentHostRequest)
    {
        try {
            $requestName = $tournamentHostRequest->tournament_title;

            // --- PERUBAHAN DI SINI ---
            // Mengganti delete() menjadi forceDelete()
            // Ini akan menghapus data secara permanen, sesuai dengan tombol Anda
            // $tournamentHostRequest->delete(); // Ini hanya soft delete (ke tong sampah)
            $tournamentHostRequest->forceDelete(); // Ini HAPUS PERMANEN

            return redirect()->route('admin.host-requests.index')
                             ->with('success', 'Permintaan host untuk "' . $requestName . '" berhasil dihapus secara permanen.');

        } catch (\Exception $e) {
            Log::error('Error deleting host request (ID: ' . $tournamentHostRequest->id . '): ' . $e->getMessage());
            return redirect()->route('admin.host-requests.index')
                             ->with('error', 'Gagal menghapus permintaan host.');
        }
    }
}