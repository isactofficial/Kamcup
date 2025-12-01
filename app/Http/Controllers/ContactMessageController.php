<?php

namespace App\Http\Controllers;

use App\Models\ContactMessage;
use Illuminate\Http\Request;

class ContactMessageController extends Controller
{
    // Public: store a new message
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'subject' => 'nullable|string|max:255',
            'message' => 'required|string|max:5000',
        ]);

        ContactMessage::create($validated);

        return back()->with('success', 'Pesan berhasil dikirim. Terima kasih!');
    }

    // Admin: list messages
    public function index(Request $request)
    {
        $filter = $request->get('filter', 'all');

        $query = ContactMessage::query()->latest();
        if ($filter === 'unread') {
            $query->where('is_read', false);
        } elseif ($filter === 'read') {
            $query->where('is_read', true);
        }

        $messages = $query->paginate(15)->withQueryString();

        return view('admin.messages.index', [
            'messages' => $messages,
            'filter' => $filter,
        ]);
    }

    // Admin: show one message and mark as read
    public function show(ContactMessage $message)
    {
        if (!$message->is_read) {
            $message->is_read = true;
            $message->read_at = now();
            $message->save();
        }

        return view('admin.messages.show', compact('message'));
    }

    // Admin: toggle read/unread
    public function toggle(ContactMessage $message)
    {
        $message->is_read = !$message->is_read;
        $message->read_at = $message->is_read ? now() : null;
        $message->save();
        $text = $message->is_read
            ? 'Status pesan berhasil diubah menjadi sudah dibaca.'
            : 'Status pesan berhasil diubah menjadi belum dibaca.';
        return back()->with('success', $text);
    }

    // Admin: delete message
    public function destroy(ContactMessage $message)
    {
        $message->delete();
        return back()->with('success', 'Pesan berhasil dihapus.');
    }
}
