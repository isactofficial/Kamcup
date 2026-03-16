<?php

namespace App\Http\Controllers;

use App\Models\Community;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;

class CommunityController extends Controller
{
    /**
     * Show the form for creating a new community (User version).
     */
    public function create()
    {
        return view('User2026.communities.create');
    }

    /**
     * Store a newly created community in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'category' => 'required|string',
            'description' => 'nullable|string',
            'status' => 'required|in:public,private',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $community = new Community();
        $community->user_id = Auth::id();
        $community->name = $request->name;
        $community->slug = Str::slug($request->name) . '-' . time();
        $community->category = $request->category;
        $community->description = $request->description;
        $community->status = $request->status;
        $community->is_official = Auth::user()->isAdmin(); // Automated based on logged in user

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

    /**
     * Show the form for creating a new community (Admin version).
     */
    public function adminCreate()
    {
        return view('Dashboard2026.communities.create');
    }
}
