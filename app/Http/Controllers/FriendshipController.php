<?php

namespace App\Http\Controllers;

use App\Events\PrivateMessageSent;
use App\Models\Friendship;
use App\Models\PrivateMessage;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class FriendshipController extends Controller
{
    public function index()
    {
        $userId = Auth::id();
        $friends = $this->getBidirectionalFriends($userId);

        // Separate logic for finding friends from BOTH sides of the many-to-many relationship
        $friendList = Auth::user()->friends; // This only gets one side currently based on my model.
        
        // Let's refine the model and controller to handle bidirectional friendships
        $friends = $this->getBidirectionalFriends($userId);
        
        $pendingRequests = Friendship::where('friend_id', $userId)
                                    ->where('status', 'pending')
                                    ->with('sender.profile')
                                    ->get();

        return view('User2026.teman', compact('friends', 'pendingRequests'));
    }

    private function getBidirectionalFriends($userId)
    {
        $friendIds = Friendship::where('status', 'accepted')
                        ->where(function($q) use ($userId) {
                            $q->where('user_id', $userId)
                              ->orWhere('friend_id', $userId);
                        })
                        ->get()
                        ->map(function($f) use ($userId) {
                            return $f->user_id == $userId ? $f->friend_id : $f->user_id;
                        });

        $currentUserCommunities = Auth::user()->joinedCommunities;
        $currentUserCommunityIds = $currentUserCommunities->pluck('id')->toArray();
        
        return User::whereIn('id', $friendIds)->with('profile', 'joinedCommunities')->get()->map(function($user) use ($currentUserCommunities, $currentUserCommunityIds) {
            $user->member_since = $user->created_at->format('M d, Y');
            
            $friendCommunityIds = $user->joinedCommunities->pluck('id')->toArray();
            $mutualIds = array_intersect($currentUserCommunityIds, $friendCommunityIds);
            
            $user->mutual_communities = $currentUserCommunities->whereIn('id', $mutualIds)->map(function($c) {
                return [
                    'id' => $c->id,
                    'name' => $c->name,
                    'image' => $c->image ? asset('storage/' . $c->image) : null
                ];
            })->values();
            
            $user->mutual_communities_count = count($mutualIds);
            return $user;
        });
    }

    public function search(Request $request)
    {
        $query = $request->get('query');
        $userId = Auth::id();

        if (empty($query)) return response()->json([]);

        $users = User::where('name', 'LIKE', "%{$query}%")
                     ->where('id', '!=', $userId)
                     ->with('profile')
                     ->limit(10)
                     ->get();

        // Check friendship status for each user
        $users->map(function($user) use ($userId) {
            $friendship = Friendship::where(function($q) use ($userId, $user) {
                $q->where('user_id', $userId)->where('friend_id', $user->id);
            })->orWhere(function($q) use ($userId, $user) {
                $q->where('user_id', $user->id)->where('friend_id', $userId);
            })->first();

            $user->friendship_status = $friendship ? $friendship->status : 'none';
            // Also identify if the current user is the one who SENT the request
            $user->is_sender = $friendship && $friendship->user_id == $userId;
            return $user;
        });

        return response()->json($users);
    }

    public function addFriend(User $user)
    {
        $userId = Auth::id();
        
        $exists = Friendship::where(function($q) use ($userId, $user) {
            $q->where('user_id', $userId)->where('friend_id', $user->id);
        })->orWhere(function($q) use ($userId, $user) {
            $q->where('user_id', $user->id)->where('friend_id', $userId);
        })->exists();

        if ($exists) {
            return response()->json(['error' => 'Permintaan sudah terkirim atau sudah berteman.'], 422);
        }

        Friendship::create([
            'user_id' => $userId,
            'friend_id' => $user->id,
            'status' => 'pending'
        ]);

        return response()->json(['success' => 'Permintaan teman terkirim!']);
    }

    public function acceptFriend(Friendship $friendship)
    {
        if ($friendship->friend_id != Auth::id()) {
            abort(403);
        }

        $friendship->update(['status' => 'accepted']);

        return back()->with('success', 'Sekarang kamu berteman!');
    }

    public function removeFriend(User $user)
    {
        $userId = Auth::id();
        
        Friendship::where(function($q) use ($userId, $user) {
            $q->where('user_id', $userId)->where('friend_id', $user->id);
        })->orWhere(function($q) use ($userId, $user) {
            $q->where('user_id', $user->id)->where('friend_id', $userId);
        })->delete();

        return back()->with('success', 'Berhasil menghapus pertemanan.');
    }

    public function getMessages(User $friend)
    {
        $userId = Auth::id();
        
        $messages = PrivateMessage::where(function($q) use ($userId, $friend) {
            $q->where('sender_id', $userId)->where('receiver_id', $friend->id);
        })->orWhere(function($q) use ($userId, $friend) {
            $q->where('sender_id', $friend->id)->where('receiver_id', $userId);
        })->orderBy('created_at', 'asc')->get();

        // Mark as read
        PrivateMessage::where('sender_id', $friend->id)
                      ->where('receiver_id', $userId)
                      ->where('is_read', false)
                      ->update(['is_read' => true]);

        return response()->json($messages);
    }

    public function sendMessage(Request $request, User $friend)
    {
        $request->validate(['message' => 'required|string']);

        $msg = PrivateMessage::create([
            'sender_id' => Auth::id(),
            'receiver_id' => $friend->id,
            'message' => $request->message
        ]);

        broadcast(new PrivateMessageSent($msg))->toOthers();

        return response()->json($msg);
    }

    public function sendImageMessage(Request $request, User $friend)
    {
        try {
            $request->validate([
                'image' => 'required|image|max:2048', // Removed mimes restriction
                'message' => 'nullable|string'
            ]);

            // Upload image
            $imagePath = $request->file('image')->store('chat-images', 'public');

            $msg = PrivateMessage::create([
                'sender_id' => Auth::id(),
                'receiver_id' => $friend->id,
                'message' => $request->message ?? '',
                'image_path' => $imagePath
            ]);

            broadcast(new PrivateMessageSent($msg))->toOthers();

            return response()->json([
                'success' => true,
                'message' => $msg
            ]);

        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Upload failed: ' . $e->getMessage()
            ], 500);
        }
    }
}
