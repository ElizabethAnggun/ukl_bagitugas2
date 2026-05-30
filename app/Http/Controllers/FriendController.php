<?php

namespace App\Http\Controllers;

use App\Models\Friend;
use App\Models\User;
use App\Models\Notification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class FriendController extends Controller
{
    /**
     * Tampilkan daftar teman dan permintaan pertemanan
     */
    public function index()
    {
        $user = Auth::user();
        
        // Ambil ID teman (accepted)
        $friendIds = Friend::where(function($q) use ($user) {
                $q->where('sender_id', $user->id)->orWhere('receiver_id', $user->id);
            })
            ->where('status', 'accepted')
            ->get()
            ->map(function($f) use ($user) {
                return $f->sender_id === $user->id ? $f->receiver_id : $f->sender_id;
            });

        $friends = User::whereIn('id', $friendIds)->get();
        
        $pendingRequests = Friend::with('sender')
            ->where('receiver_id', $user->id)
            ->where('status', 'pending')
            ->get();

        $sentRequests = Friend::with('receiver')
            ->where('sender_id', $user->id)
            ->where('status', 'pending')
            ->get();

        return view('friends.index', compact('friends', 'pendingRequests', 'sentRequests'));
    }

    /**
     * Kirim permintaan pertemanan
     */
    public function sendRequest(Request $request)
    {
        $request->validate([
            'email' => 'required|email|exists:users,email',
        ], [
            'email.exists' => 'User dengan email tersebut tidak ditemukan.',
        ]);

        $receiver = User::where('email', $request->email)->first();
        $sender = Auth::user();

        if ($receiver->id === $sender->id) {
            return redirect()->back()->with('error', 'Anda tidak bisa mengirim permintaan pertemanan ke diri sendiri.');
        }

        // Cek apakah sudah ada hubungan pertemanan
        $existing = Friend::where(function($q) use ($sender, $receiver) {
                $q->where('sender_id', $sender->id)->where('receiver_id', $receiver->id);
            })
            ->orWhere(function($q) use ($sender, $receiver) {
                $q->where('sender_id', $receiver->id)->where('receiver_id', $sender->id);
            })
            ->first();

        if ($existing) {
            if ($existing->status === 'accepted') {
                return redirect()->back()->with('error', 'Anda sudah berteman dengan user ini.');
            }
            if ($existing->status === 'pending') {
                return redirect()->back()->with('error', 'Permintaan pertemanan sudah ada.');
            }
            // Jika rejected, izinkan kirim ulang (update status ke pending)
            $existing->update(['sender_id' => $sender->id, 'receiver_id' => $receiver->id, 'status' => 'pending']);
        } else {
            Friend::create([
                'sender_id' => $sender->id,
                'receiver_id' => $receiver->id,
                'status' => 'pending',
            ]);
        }

        // Kirim notifikasi
        Notification::create([
            'user_id' => $receiver->id,
            'title' => 'Permintaan Pertemanan',
            'message' => $sender->name . ' mengirimkan Anda permintaan pertemanan.',
            'link' => route('friends.index'),
        ]);

        return redirect()->back()->with('success', 'Permintaan pertemanan berhasil dikirim!');
    }

    /**
     * Terima permintaan pertemanan
     */
    public function acceptRequest(Friend $friend)
    {
        if ($friend->receiver_id !== Auth::id()) {
            return redirect()->back()->with('error', 'Akses ditolak.');
        }

        $friend->update(['status' => 'accepted']);

        // Notif ke pengirim
        Notification::create([
            'user_id' => $friend->sender_id,
            'title' => 'Permintaan Diterima',
            'message' => Auth::user()->name . ' menerima permintaan pertemanan Anda.',
            'link' => route('friends.index'),
        ]);

        return redirect()->back()->with('success', 'Permintaan pertemanan diterima!');
    }

    /**
     * Tolak permintaan pertemanan
     */
    public function rejectRequest(Friend $friend)
    {
        if ($friend->receiver_id !== Auth::id()) {
            return redirect()->back()->with('error', 'Akses ditolak.');
        }

        $friend->update(['status' => 'rejected']);

        return redirect()->back()->with('success', 'Permintaan pertemanan ditolak.');
    }

    /**
     * Hapus pertemanan
     */
    public function unfriend(User $user)
    {
        $currentUserId = Auth::id();
        
        Friend::where(function($q) use ($currentUserId, $user) {
                $q->where('sender_id', $currentUserId)->where('receiver_id', $user->id);
            })
            ->orWhere(function($q) use ($currentUserId, $user) {
                $q->where('sender_id', $user->id)->where('receiver_id', $currentUserId);
            })
            ->delete();

        return redirect()->back()->with('success', 'Berhasil menghapus pertemanan.');
    }
}
