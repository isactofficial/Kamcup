<?php

namespace App\Http\Controllers;

use App\Models\Community;
use App\Models\Feed;
use App\Models\User;
use App\Models\CommunityMessage;
use App\Models\CommunityReport;
use App\Events\MessageSent;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;

class CommunityController extends Controller
{
    /*Show the form to User.*/
    public function create()
    {
        return view('User2026.communities.create');
    }

    /*Display the specified community.*/
    public function show(Community $community)
    {
        $community->load(['creator', 'members', 'feeds.user.profile']);
        $community->loadCount(['members', 'feeds']);
        
        $userMember = $community->members()->where('user_id', Auth::id())->first();
        $isJoined = $userMember && $userMember->pivot->status === 'approved';
        $isPending = $userMember && $userMember->pivot->status === 'pending';
        $isCommunityAdmin = ($userMember && $userMember->pivot->role === 'admin') || $community->user_id === Auth::id();
        
        $pendingMembers = collect();
        if ($isCommunityAdmin) {
            $pendingMembers = $community->members()->wherePivot('status', 'pending')->get();
        }
        
        return view('User2026.communities.show', compact('community', 'isJoined', 'isPending', 'isCommunityAdmin', 'pendingMembers'));
    }

    /**
     * Store a new post for the community.
     */
    public function storePost(Request $request, Community $community)
    {
        $userMember = $community->members()->where('user_id', Auth::id())->first();
        $isCommunityAdmin = ($userMember && $userMember->pivot->role === 'admin') || $community->user_id === Auth::id();

        if (!$isCommunityAdmin) {
            return redirect()->back()->with('error', 'Hanya admin komunitas yang dapat memposting.');
        }

        $request->validate([
            'title' => 'nullable|string|max:255',
            'content' => 'required_without:image|nullable|string',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
        ]);

        $post = new Feed();
        $post->user_id = Auth::id();
        $post->community_id = $community->id;
        $post->title = $request->title;
        $post->content = $request->content;

        if ($request->hasFile('image')) {
            $path = $request->file('image')->store('feeds', 'public');
            $post->image = $path;
        }

        $post->save();

        return redirect()->back()->with('success', 'Postingan komunitas berhasil dipublikasikan!');
    }

    /**
     * Update member role (admin or member).
     */
    public function updateMemberRole(Request $request, Community $community, User $user)
    {
        $currentUserMember = $community->members()->where('user_id', Auth::id())->first();
        $isCurrentUserAdmin = ($currentUserMember && $currentUserMember->pivot->role === 'admin') || $community->user_id === Auth::id();

        if (!$isCurrentUserAdmin) {
            return redirect()->back()->with('error', 'Unauthorized.');
        }

        // Restriction: can't change creator's role
        if ($user->id === $community->user_id) {
            return redirect()->back()->with('error', 'Role creator tidak dapat diubah.');
        }

        $request->validate(['role' => 'required|in:admin,member']);
        $community->members()->updateExistingPivot($user->id, ['role' => $request->role]);

        return redirect()->back()->with('success', 'Status anggota berhasil diupdate.');
    }

    /**
     * Remove member from community.
     */
    public function removeMember(Community $community, User $user)
    {
        $currentUserMember = $community->members()->where('user_id', Auth::id())->first();
        $isCurrentUserAdmin = ($currentUserMember && $currentUserMember->pivot->role === 'admin') || $community->user_id === Auth::id();

        if (!$isCurrentUserAdmin && Auth::id() !== $user->id) {
            return redirect()->back()->with('error', 'Unauthorized.');
        }

        // Restriction: can't remove creator
        if ($user->id === $community->user_id) {
            return redirect()->back()->with('error', 'Creator tidak dapat dikeluarkan.');
        }

        $community->members()->detach($user->id);

        return redirect()->back()->with('success', 'Anggota berhasil dikeluarkan.');
    }

    /**
     * Approve member join request.
     */
    public function approveMember(Community $community, User $user)
    {
        $currentUserMember = $community->members()->where('user_id', Auth::id())->first();
        $isCurrentUserAdmin = ($currentUserMember && $currentUserMember->pivot->role === 'admin') || $community->user_id === Auth::id();

        if (!$isCurrentUserAdmin) {
            return redirect()->back()->with('error', 'Unauthorized.');
        }

        $community->members()->updateExistingPivot($user->id, ['status' => 'approved']);

        return redirect()->back()->with('success', 'Anggota berhasil disetujui.');
    }

    /**
     * Join a community.
     */
    public function join(Community $community)
    {
        $userId = Auth::id();
        
        if ($community->members()->where('user_id', $userId)->exists()) {
            return redirect()->back()->with('error', 'Anda sudah bergabung atau sedang menunggu persetujuan.');
        }

        $status = ($community->status === 'public') ? 'approved' : 'pending';
        
        $community->members()->attach($userId, [
            'role' => 'member',
            'status' => $status,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        if ($status === 'pending') {
            return redirect()->back()->with('success', 'Permintaan bergabung telah dikirim. Menunggu persetujuan admin.');
        }

        return redirect()->back()->with('success', 'Berhasil bergabung dengan komunitas!');
    }

    /**
     * Send a new chat message.
     */
    public function sendMessage(Request $request, Community $community)
    {
        $request->validate(['message' => 'required|string']);

        $chatMessage = $community->messages()->create([
            'user_id' => Auth::id(),
            'message' => $request->message
        ]);

        $chatMessage->load('user');

        broadcast(new MessageSent($chatMessage))->toOthers();

        return response()->json($chatMessage);
    }

    /**
     * Get chat messages.
     */
    public function getMessages(Community $community)
    {
        return $community->messages()->with('user')->latest()->limit(50)->get()->reverse()->values();
    }

    /**
     * Report a community.
     */
    public function report(Request $request, Community $community)
    {
        $lastMessages = $community->messages()
            ->with('user')
            ->latest()
            ->limit(5)
            ->get()
            ->map(function($msg) {
                return [
                    'user' => $msg->user->name ?? 'Unknown',
                    'message' => $msg->message,
                    'time' => $msg->created_at->toDateTimeString()
                ];
            });

        CommunityReport::create([
            'user_id' => Auth::id(),
            'community_id' => $community->id,
            'reason' => $request->reason ?? 'Spam/Inappropriate',
            'chats_snapshot' => $lastMessages->toArray()
        ]);

        return redirect()->back()->with('success', 'Komunitas telah dilaporkan. Admin akan segera meninjau.');
    }

    /**
     * Display all community reports for admin.
     */
    public function adminReports()
    {
        $reports = CommunityReport::with(['user', 'community'])->latest()->get();
        return view('Dashboard2026.community-reports', compact('reports'));
    }

    /**
     * Dismiss or delete report.
     */
    public function dismissReport(CommunityReport $report)
    {
        $report->delete();
        return redirect()->back()->with('success', 'Laporan telah dihapus/diselesaikan.');
    }

    /*Store a newly created community in storage.*/
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'category' => 'required|string',
            'description' => 'nullable|string',
            'status' => 'required|in:public,private',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
        ]);

        $community = new Community();
        $community->user_id = Auth::id();
        $community->name = $request->name;
        $community->slug = Str::slug($request->name) . '-' . time();
        $community->category = $request->category;
        $community->description = $request->description;
        $community->status = $request->status;
        $community->is_official = Auth::user()->isAdmin(); // based on logged in user

        if ($request->hasFile('image')) {
            $path = $request->file('image')->store('communities', 'public');
            $community->image = $path;
        }

        $community->save();

        // Automatically join as admin
        $community->members()->attach(Auth::id(), ['role' => 'admin', 'status' => 'approved']);

        $message = Auth::user()->isAdmin() ? 'Komunitas Official berhasil dibuat!' : 'Komunitas berhasil dibuat!';
        
        return Auth::user()->isAdmin() 
            ? redirect()->route('admin.userpages.komunitas')->with('success', $message)
            : redirect()->route('user2026.komunitas')->with('success', $message);
    }

    /*Display communities for admin.*/
    public function adminIndex(Request $request)
    {
        $sort = $request->input('sort', 'latest');
        $query = Community::with(['creator'])->withCount('members');

        switch ($sort) {
            case 'members':
                $query->orderBy('members_count', 'desc');
                break;
            case 'official':
                $query->orderBy('is_official', 'desc');
                break;
            case 'latest':
            default:
                $query->orderBy('created_at', 'desc');
                break;
        }

        $communities = $query->paginate(10);

        return view('Dashboard2026.komunitas', compact('communities'));
    }

    /*Show the form for Admin.*/
    public function adminCreate()
    {
        return view('Dashboard2026.communities.create');
    }

    /*Remove community from storage.*/
    public function destroy(Community $community)
    {
        // Delete image if exists
        if ($community->image) {
            \Illuminate\Support\Facades\Storage::disk('public')->delete($community->image);
        }

        $community->delete();

        return redirect()->back()->with('success', 'Komunitas berhasil dihapus!');
    }
}
