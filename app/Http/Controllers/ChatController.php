<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Message;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class ChatController extends Controller
{
    /**
     * ✅ 1. Halaman chat user ke admin
     */
    public function index()
    {
        $user = Auth::user();
        $admin = User::where('role', 'admin')->firstOrFail();

        // Ambil semua pesan antara user dan admin
        $messages = Message::where(function ($query) use ($user, $admin) {
                $query->where('sender_id', $user->id)->where('receiver_id', $admin->id);
            })->orWhere(function ($query) use ($user, $admin) {
                $query->where('sender_id', $admin->id)->where('receiver_id', $user->id);
            })
            ->orderBy('created_at', 'asc')
            ->get();

        // Tandai semua pesan dari admin ke user ini sebagai sudah dibaca
        Message::where('receiver_id', $user->id)
            ->where('sender_id', $admin->id)
            ->where('is_read', false)
            ->update(['is_read' => true]);

        return view('users.dimas_chat', compact('messages'));
    }

    /**
     * ✅ 2. Proses kirim pesan dari user ke admin
     */
    public function send(Request $request)
    {
        $request->validate(['message' => 'required|string']);

        $admin = User::where('role', 'admin')->firstOrFail();

        Message::create([
            'sender_id' => Auth::id(),
            'receiver_id' => $admin->id,
            'message' => $request->message,
            'is_read' => false,
        ]);

        return redirect()->route('chat')->with('success', 'Pesan berhasil dikirim.');
    }

    /**
     * ✅ 3. Halaman chat admin ke user tertentu
     */
    public function adminChat($userId)
    {
        $admin = Auth::user();
        $user = User::findOrFail($userId);

        // Tandai semua pesan dari user ke admin sebagai sudah dibaca
        Message::where('sender_id', $user->id)
            ->where('receiver_id', $admin->id)
            ->where('is_read', false)
            ->update(['is_read' => true]);

        // Ambil semua pesan antara user dan admin
        $messages = Message::where(function ($query) use ($admin, $user) {
                $query->where('sender_id', $admin->id)->where('receiver_id', $user->id);
            })->orWhere(function ($query) use ($admin, $user) {
                $query->where('sender_id', $user->id)->where('receiver_id', $admin->id);
            })
            ->orderBy('created_at', 'asc')
            ->get();

        return view('admin.dimas_chat_admin', compact('messages', 'user', 'admin', 'userId'));
    }

    /**
     * ✅ 4. Proses kirim pesan dari admin ke user
     */
    public function adminSend(Request $request, $userId)
    {
        $request->validate(['message' => 'required|string']);

        Message::create([
            'sender_id' => Auth::id(),
            'receiver_id' => $userId,
            'message' => $request->message,
            'is_read' => false,
        ]);

        return redirect()->route('admin.chat', ['userId' => $userId])
            ->with('new_message_from_admin', 'Pesan berhasil dikirim ke pengguna.');
    }

    /**
     * ✅ 5. Menampilkan daftar user yang pernah chat dengan admin
     */
    public function chatUserList()
    {
        $adminId = Auth::id();

        $userIds = Message::where('sender_id', $adminId)
            ->orWhere('receiver_id', $adminId)
            ->pluck('sender_id')
            ->merge(
                Message::where('sender_id', '!=', $adminId)
                    ->where('receiver_id', $adminId)
                    ->pluck('receiver_id')
            )
            ->unique()
            ->filter(fn ($id) => $id != $adminId)
            ->values();

        $users = User::whereIn('id', $userIds)->get();

        return view('admin.dimas_chat_user', compact('users'));
    }
}
