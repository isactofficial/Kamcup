<?php

namespace App\Http\Controllers;

use App\Models\Donation;
use App\Models\Tournament;
use App\Models\Article;
use App\Models\Gallery;
use App\Models\Sponsor;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class DonationController extends Controller
{
    /**
     * Constructor
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Menampilkan form donasi.
     */
    public function create()
    {
        return view('donations.create');
    }

    /**
     * Menyimpan data donasi baru.
     */
    public function store(Request $request)
    {
        // Validasi
        $validator = Validator::make($request->all(), [
            'donor_name' => 'required|string|max:255',
            'amount' => 'required|numeric|min:1000',
            'proof_image' => 'required|image|mimes:jpg,jpeg,png|max:2048',
            'foto_donatur' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
            'message' => 'nullable|string|max:1000',
        ], [
            'donor_name.required' => 'Nama pengirim donasi wajib diisi.',
            'amount.required' => 'Jumlah donasi wajib diisi.',
            'amount.numeric' => 'Jumlah donasi harus berupa angka.',
            'amount.min' => 'Jumlah donasi minimal Rp 1.000.',
            'proof_image.required' => 'Bukti transfer wajib diupload.',
            'proof_image.image' => 'File harus berupa gambar.',
            'proof_image.mimes' => 'Format gambar harus jpg, jpeg, atau png.',
            'proof_image.max' => 'Ukuran gambar maksimal 2MB.',
            'foto_donatur.image' => 'File harus berupa gambar.',
            'foto_donatur.mimes' => 'Format gambar harus jpg, jpeg, atau png.',
            'foto_donatur.max' => 'Ukuran gambar maksimal 2MB.',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        try {
            $user = Auth::user();
            
            // Upload gambar bukti transfer
            $proofImagePath = null;
            if ($request->hasFile('proof_image')) {
                $proofImagePath = $request->file('proof_image')->store('donations/proofs', 'public');
            }

            // Upload foto donatur (opsional)
            $fotoDonaturPath = null;
            if ($request->hasFile('foto_donatur')) {
                $fotoDonaturPath = $request->file('foto_donatur')->store('donations/photos', 'public');
            }

            // Simpan data donasi
            Donation::create([
                'user_id' => $user->id,
                'donor_name' => $request->donor_name,
                'email' => $user->email,
                'amount' => $request->amount,
                'proof_image' => $proofImagePath,
                'foto_donatur' => $fotoDonaturPath,
                'message' => $request->message,
                'status' => 'pending'
            ]);

            return redirect()->back()->with('success', 
                'Terima kasih! Donasi Anda telah berhasil dikirim dan sedang menunggu verifikasi.'
            );
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Terjadi kesalahan saat memproses donasi Anda.')->withInput();
        }
    }

    /**
     * Menampilkan semua donasi (untuk admin).
     */
    public function index(Request $request)
    {
        // 1. Logika untuk data donasi
        $query = Donation::with('user');
        
        if ($request->has('status') && $request->status) {
            $query->where('status', $request->status);
        }
        
        if ($request->has('search') && $request->search) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('donor_name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%")
                  ->orWhere('amount', 'like', "%{$search}%")
                  ->orWhereHas('user', function($userQuery) use ($search) {
                      $userQuery->where('name', 'like', "%{$search}%");
                  });
            });
        }
        
        // Sorting
        $sort = $request->get('sort', 'latest');
        if ($sort === 'oldest') {
            $query->orderBy('created_at', 'asc');
        } elseif ($sort === 'amount_high') {
            $query->orderBy('amount', 'desc');
        } elseif ($sort === 'amount_low') {
            $query->orderBy('amount', 'asc');
        } else {
            $query->orderBy('created_at', 'desc');
        }
        
        $donations = $query->paginate(20);

        // 2. Tambahkan semua data lain yang dibutuhkan oleh view 'index.blade.php'
        $next_match = Tournament::where('registration_start', '>=', now())
                                ->orderBy('registration_start', 'asc')
                                ->first();
        
        $latest_articles = Article::latest()->take(5)->get();
        $populer_articles = Article::orderBy('views', 'desc')->take(5)->get(); 
        
        $events = Tournament::where('status', '!=', 'completed')
                            ->orderBy('registration_start', 'asc')
                            ->take(5)
                            ->get();

        $galleries = Gallery::latest()->take(10)->get();
        $sponsorData = Sponsor::all()->groupBy('size');
        $chunk_size = 3;

        // 3. Kirim SEMUA data ke view
        return view('donations.index', compact(
            'donations', 
            'next_match',
            'latest_articles',
            'populer_articles',
            'events',
            'galleries',
            'sponsorData',
            'chunk_size'
        ));
    }

    /**
     * Menampilkan detail donasi.
     */
    public function show(Donation $donation)
    {
        $donation->load('user');
        return view('donations.show', compact('donation'));
    }

    /**
     * Memperbarui status donasi.
     */
    public function updateStatus(Request $request, Donation $donation)
    {
        $request->validate([
            'status' => 'required|in:pending,approved,rejected'
        ]);
        
        $donation->update(['status' => $request->status]);
        
        return redirect()->back()->with('success', 'Status donasi berhasil diperbarui.');
    }
    
    /**
     * Menghapus data donasi.
     */
    public function destroy($id)
    {
        try {
            $donation = Donation::findOrFail($id);
            
            // Hapus file gambar bukti jika ada
            if ($donation->proof_image && Storage::disk('public')->exists($donation->proof_image)) {
                Storage::disk('public')->delete($donation->proof_image);
            }

            // Hapus file foto donatur jika ada
            if ($donation->foto_donatur && Storage::disk('public')->exists($donation->foto_donatur)) {
                Storage::disk('public')->delete($donation->foto_donatur);
            }
            
            $donation->delete();
            
            return redirect()->route('admin.donations.index')
                ->with('success', 'Data donasi berhasil dihapus!');
        } catch (\Exception $e) {
            return redirect()->route('admin.donations.index')
                ->with('error', 'Gagal menghapus data: ' . $e->getMessage());
        }
    }
    
    /**
     * Statistik donasi.
     */
    public function statistics()
    {
        $stats = [
            'total' => Donation::count(),
            'total_amount' => Donation::where('status', 'approved')->sum('amount'),
            'pending' => Donation::where('status', 'pending')->count(),
            'approved' => Donation::where('status', 'approved')->count(),
            'rejected' => Donation::where('status', 'rejected')->count(),
            'this_month' => Donation::whereMonth('created_at', now()->month)
                ->whereYear('created_at', now()->year)
                ->count(),
            'this_month_amount' => Donation::whereMonth('created_at', now()->month)
                ->whereYear('created_at', now()->year)
                ->where('status', 'approved')
                ->sum('amount')
        ];
        
        return response()->json($stats);
    }

    /**
     * Export donasi ke CSV.
     */
    public function export()
    {
        $donations = Donation::with('user')->orderBy('created_at', 'desc')->get();
        $filename = 'donations_' . now()->format('Y_m_d') . '.csv';
        
        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => "attachment; filename=\"$filename\"",
        ];
        
        $callback = function() use ($donations) {
            $file = fopen('php://output', 'w');
            fputcsv($file, ['ID', 'User ID', 'Nama Donatur', 'Email', 'Jumlah Donasi', 'Status', 'Tanggal']);
            
            foreach ($donations as $donation) {
                fputcsv($file, [
                    $donation->id, 
                    $donation->user_id, 
                    $donation->donor_name, 
                    $donation->email, 
                    'Rp ' . number_format($donation->amount, 0, ',', '.'),
                    ucfirst($donation->status), 
                    $donation->created_at->format('d/m/Y H:i')
                ]);
            }
            
            fclose($file);
        };
        
        return response()->stream($callback, 200, $headers);
    }
}