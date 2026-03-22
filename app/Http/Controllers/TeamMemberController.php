<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Storage;
use Illuminate\Contracts\Encryption\DecryptException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Validation\Rule;
use App\Models\Team;
use App\Models\TeamMember;
use Illuminate\Support\Facades\Log;

class TeamMemberController extends Controller
{
    public function create(string $encryptedTeamId)
    {
        try {
            $teamId = Crypt::decryptString($encryptedTeamId);
            $team   = Team::findOrFail($teamId);
        } catch (DecryptException $e) {
            return redirect()->route('profile.index')->with('error', 'Link pembuatan anggota tim tidak valid.');
        } catch (ModelNotFoundException $e) {
            return redirect()->route('profile.index')->with('error', 'Tim tidak ditemukan.');
        }

        if (Auth::id() !== $team->user_id) {
            return redirect()->route('profile.index')->with('error', 'Anda tidak memiliki izin untuk menambah anggota ke tim ini.');
        }

        if ($team->members->count() >= $team->member_count) {
            return redirect()->route('profile.index')->with('info', 'Jumlah anggota tim sudah mencapai batas maksimum (' . $team->member_count . ').');
        }

        return view('front.teams.members.create', compact('team'));
    }

    public function store(Request $request, string $encryptedTeamId)
    {
        try {
            $teamId = Crypt::decryptString($encryptedTeamId);
            $team   = Team::findOrFail($teamId);
        } catch (DecryptException $e) {
            return redirect()->route('profile.index')->with('error', 'Link pembaruan anggota tim tidak valid.');
        } catch (ModelNotFoundException $e) {
            return redirect()->route('profile.index')->with('error', 'Tim tidak ditemukan.');
        }

        if (Auth::id() !== $team->user_id) {
            return redirect()->route('profile.index')->with('error', 'Anda tidak memiliki izin untuk menambah anggota ke tim ini.');
        }

        if ($team->members->count() >= $team->member_count) {
            return redirect()->route('profile.index')->with('info', 'Jumlah anggota tim sudah mencapai batas maksimum (' . $team->member_count . ').');
        }

        $request->validate([
            'name'                  => ['required', 'string', 'max:255'],
            'birthdate'             => ['nullable', 'date', 'before_or_equal:today'],
            'gender'                => ['required', Rule::in(['male', 'female', 'other'])],
            'position'              => ['nullable', 'string', 'max:255'],
            'jersey_number'         => ['nullable', 'integer', 'min:1', 'max:99'],
            'contact'               => ['nullable', 'string', 'max:255'],
            'email'                 => ['nullable', 'email', 'max:255', Rule::unique('team_members', 'email')],
            // ↓ nullable semua — foto bisa dari base64 (cropped) atau file biasa
            'photo'                 => ['nullable', 'image', 'mimes:jpeg,png,jpg,gif,webp', 'max:2048'],
            'cropped_member_photo'  => ['nullable', 'string'],
        ], [
            'name.required'   => 'Nama anggota wajib diisi.',
            'gender.required' => 'Jenis kelamin anggota wajib dipilih.',
            'gender.in'       => 'Jenis kelamin anggota tidak valid.',
            'email.unique'    => 'Email ini sudah terdaftar untuk anggota tim lain.',
            'photo.image'     => 'File foto harus berupa gambar.',
            'photo.mimes'     => 'Format foto yang diizinkan adalah jpeg, png, jpg, gif, atau webp.',
            'photo.max'       => 'Ukuran foto tidak boleh melebihi 2MB.',
        ]);

        try {
            $photoPath = null;

            // ── Prioritas 1: hasil crop (base64) ─────────────────────────
            if ($request->filled('cropped_member_photo')) {
                $base64String = $request->input('cropped_member_photo');
                if (str_contains($base64String, ';base64,')) {
                    [, $base64Data] = explode(';base64,', $base64String);
                    $imageData = base64_decode($base64Data);
                    if ($imageData !== false) {
                        $filename  = 'team_member_photos/member_' . time() . '_' . uniqid() . '.jpg';
                        Storage::disk('public')->put($filename, $imageData);
                        $photoPath = $filename;
                    }
                }

            // ── Prioritas 2: file upload biasa ────────────────────────────
            } elseif ($request->hasFile('photo')) {
                $photoPath = $request->file('photo')->store('team_member_photos', 'public');
            }

            $team->members()->create([
                'name'          => $request->name,
                'birthdate'     => $request->birthdate,
                'gender'        => $request->gender,
                'position'      => $request->position,
                'jersey_number' => $request->jersey_number,
                'contact'       => $request->contact,
                'email'         => $request->email,
                'photo'         => $photoPath,
            ]);

            return redirect()->route('profile.index')
                             ->with('success', 'Anggota tim "' . $request->name . '" berhasil ditambahkan ke tim ' . $team->name . '!');

        } catch (\Exception $e) {
            Log::error("Error storing team member: " . $e->getMessage(), [
                'team_id'      => $team->id,
                'request_data' => $request->except(['photo', 'cropped_member_photo']),
            ]);
            return back()->withInput()->with('error', 'Gagal menambahkan anggota tim. Mohon periksa kembali input Anda.');
        }
    }

    public function edit(string $encryptedTeamId, string $encryptedMemberId)
    {
        try {
            $teamId   = Crypt::decryptString($encryptedTeamId);
            $team     = Team::findOrFail($teamId);
            $memberId = Crypt::decryptString($encryptedMemberId);
            $member   = TeamMember::where('team_id', $team->id)->findOrFail($memberId);
        } catch (DecryptException $e) {
            return redirect()->route('profile.index')->with('error', 'Link pengeditan anggota tim tidak valid.');
        } catch (ModelNotFoundException $e) {
            return redirect()->route('profile.index')->with('error', 'Tim atau anggota tim tidak ditemukan.');
        }

        if (Auth::id() !== $team->user_id) {
            return redirect()->route('profile.index')->with('error', 'Anda tidak memiliki izin untuk mengedit anggota tim ini.');
        }

        return view('front.teams.members.edit', compact('team', 'member'));
    }

    public function update(Request $request, string $encryptedTeamId, string $encryptedMemberId)
    {
        try {
            $teamId   = Crypt::decryptString($encryptedTeamId);
            $team     = Team::findOrFail($teamId);
            $memberId = Crypt::decryptString($encryptedMemberId);
            $member   = TeamMember::where('team_id', $team->id)->findOrFail($memberId);
        } catch (DecryptException $e) {
            return redirect()->route('profile.index')->with('error', 'Link pembaruan anggota tim tidak valid.');
        } catch (ModelNotFoundException $e) {
            return redirect()->route('profile.index')->with('error', 'Tim atau anggota tim tidak ditemukan.');
        }

        if (Auth::id() !== $team->user_id) {
            return redirect()->route('profile.index')->with('error', 'Anda tidak memiliki izin untuk memperbarui anggota tim ini.');
        }

        $request->validate([
            'name'                  => ['required', 'string', 'max:255'],
            'birthdate'             => ['nullable', 'date', 'before_or_equal:today'],
            'gender'                => ['required', Rule::in(['male', 'female', 'other'])],
            'position'              => ['nullable', 'string', 'max:255'],
            'jersey_number'         => ['nullable', 'integer', 'min:1', 'max:99'],
            'contact'               => ['nullable', 'string', 'max:255'],
            'email'                 => ['nullable', 'email', 'max:255', Rule::unique('team_members', 'email')->ignore($member->id)],
            'photo'                 => ['nullable', 'image', 'mimes:jpeg,png,jpg,gif,webp', 'max:2048'],
            'cropped_member_photo'  => ['nullable', 'string'],
            'clear_photo'           => ['nullable', 'boolean'],
        ], [
            'name.required'   => 'Nama anggota wajib diisi.',
            'gender.required' => 'Jenis kelamin anggota wajib dipilih.',
            'gender.in'       => 'Jenis kelamin anggota tidak valid.',
            'email.unique'    => 'Email ini sudah terdaftar untuk anggota tim lain.',
            'photo.image'     => 'File foto harus berupa gambar.',
            'photo.mimes'     => 'Format foto yang diizinkan adalah jpeg, png, jpg, gif, atau webp.',
            'photo.max'       => 'Ukuran foto tidak boleh melebihi 2MB.',
        ]);

        try {
            // ── Prioritas 1: hasil crop (base64) ─────────────────────────
            if ($request->filled('cropped_member_photo')) {
                $base64String = $request->input('cropped_member_photo');
                if (str_contains($base64String, ';base64,')) {
                    // Hapus foto lama
                    if ($member->photo && Storage::disk('public')->exists($member->photo)) {
                        Storage::disk('public')->delete($member->photo);
                    }
                    [, $base64Data] = explode(';base64,', $base64String);
                    $imageData = base64_decode($base64Data);
                    if ($imageData !== false) {
                        $filename    = 'team_member_photos/member_' . $member->id . '_' . time() . '.jpg';
                        Storage::disk('public')->put($filename, $imageData);
                        $member->photo = $filename;
                    }
                }

            // ── Prioritas 2: file upload biasa ────────────────────────────
            } elseif ($request->hasFile('photo')) {
                if ($member->photo && Storage::disk('public')->exists($member->photo)) {
                    Storage::disk('public')->delete($member->photo);
                }
                $member->photo = $request->file('photo')->store('team_member_photos', 'public');

            // ── Prioritas 3: hapus foto ───────────────────────────────────
            } elseif ($request->boolean('clear_photo')) {
                if ($member->photo && Storage::disk('public')->exists($member->photo)) {
                    Storage::disk('public')->delete($member->photo);
                }
                $member->photo = null;
            }

            $member->name          = $request->name;
            $member->birthdate     = $request->birthdate;
            $member->gender        = $request->gender;
            $member->position      = $request->position;
            $member->jersey_number = $request->jersey_number;
            $member->contact       = $request->contact;
            $member->email         = $request->email;
            $member->save();

            return redirect()->route('profile.index')
                             ->with('success', 'Anggota tim "' . $request->name . '" berhasil diperbarui!');

        } catch (\Exception $e) {
            Log::error("Error updating team member: " . $e->getMessage(), [
                'member_id'    => $member->id,
                'request_data' => $request->except(['photo', 'cropped_member_photo']),
            ]);
            return back()->withInput()->with('error', 'Gagal memperbarui anggota tim: ' . $e->getMessage());
        }
    }

    public function destroy(string $encryptedTeamId, string $encryptedMemberId)
    {
        try {
            $teamId   = Crypt::decryptString($encryptedTeamId);
            $team     = Team::findOrFail($teamId);
            $memberId = Crypt::decryptString($encryptedMemberId);
            $member   = TeamMember::where('team_id', $team->id)->findOrFail($memberId);
        } catch (DecryptException $e) {
            return redirect()->route('profile.index')->with('error', 'Link penghapusan anggota tim tidak valid.');
        } catch (ModelNotFoundException $e) {
            return redirect()->route('profile.index')->with('error', 'Tim atau anggota tim tidak ditemukan.');
        }

        if (Auth::id() !== $team->user_id) {
            return redirect()->route('profile.index')->with('error', 'Anda tidak memiliki izin untuk menghapus anggota tim ini.');
        }

        if ($team->members->count() <= 1) {
            return redirect()->route('profile.index')->with('error', 'Tim harus memiliki setidaknya satu anggota.');
        }

        try {
            if ($member->photo && Storage::disk('public')->exists($member->photo)) {
                Storage::disk('public')->delete($member->photo);
            }
            $memberName = $member->name;
            $member->delete();

            return redirect()->route('profile.index')
                             ->with('success', 'Anggota tim "' . $memberName . '" berhasil dihapus.');

        } catch (\Exception $e) {
            Log::error("Error deleting team member: " . $e->getMessage(), ['member_id' => $member->id]);
            return back()->with('error', 'Gagal menghapus anggota tim: ' . $e->getMessage());
        }
    }
}