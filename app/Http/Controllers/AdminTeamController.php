<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Team;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;

class AdminTeamController extends Controller
{
    /**
     * Menampilkan daftar semua tim dengan filter dan pagination.
     */
    public function index(Request $request)
    {
        $query = Team::with('user')->latest();

        // Filter berdasarkan status
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // Filter berdasarkan pencarian
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'LIKE', "%{$search}%")
                  ->orWhere('manager_name', 'LIKE', "%{$search}%")
                  ->orWhereHas('user', function ($userQuery) use ($search) {
                      $userQuery->where('name', 'LIKE', "%{$search}%");
                  });
            });
        }

        $teams = $query->paginate(10)->withQueryString();

        return view('admin.teams.index', compact('teams'));
    }

    /**
     * Menampilkan form untuk mengedit tim.
     */
    public function edit(Team $team)
    {
        return view('admin.teams.edit', compact('team'));
    }

    /**
     * Memperbarui data tim di database.
     */
    public function update(Request $request, Team $team)
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255', Rule::unique('teams')->ignore($team->id)],
            'logo' => ['nullable', 'image', 'mimes:jpeg,png,jpg,gif', 'max:2048'],
            'manager_name' => ['required', 'string', 'max:255'],
            'contact' => ['required', 'string', 'max:255'],
            'location' => ['required', 'string', 'max:255'],
            'gender_category' => ['required', Rule::in(['male', 'female', 'mixed'])],
            'member_count' => ['required', 'integer', 'min:1', 'max:10'],
            'description' => ['nullable', 'string'],
            'status' => ['required', Rule::in(['pending', 'approved', 'rejected'])], // Validasi status
        ]);

        $data = $request->except('logo');

        if ($request->hasFile('logo')) {
            // Hapus logo lama jika ada
            if ($team->logo && Storage::disk('public')->exists($team->logo)) {
                Storage::disk('public')->delete($team->logo);
            }
            $data['logo'] = $request->file('logo')->store('team_logos', 'public');
        }

        $team->update($data);

        return redirect()->route('admin.teams.index')->with('success', 'Tim "' . $team->name . '" berhasil diperbarui.');
    }

    /**
     * Menghapus tim dari database.
     */
    public function destroy(Team $team)
    {
        // Hapus anggota tim terkait
        // Kita asumsikan relasi 'members' sudah di-setup untuk cascade delete atau kita hapus manual
        $team->members()->delete(); 

        // Hapus file logo jika ada
        if ($team->logo && Storage::disk('public')->exists($team->logo)) {
            Storage::disk('public')->delete($team->logo);
        }

        $teamName = $team->name;
        $team->delete();

        return redirect()->route('admin.teams.index')->with('success', 'Tim "' . $teamName . '" dan semua anggotanya berhasil dihapus.');
    }
}