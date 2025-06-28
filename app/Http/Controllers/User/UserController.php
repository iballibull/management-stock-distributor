<?php

namespace App\Http\Controllers\User;

use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class UserController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->input('search') ?? '';

        $users = User::withTrashed()
            ->select('id', 'name', 'email', 'photo', 'role_id', 'deleted_at')
            ->where('name', 'LIKE', "%$search%")
            ->orWhere('email', 'LIKE', "%$search%")
            ->with('role:id,name')->paginate(10);

        return view('user.index', compact('users'));
    }

    public function update(Request $request, $userId)
    {
        try {
            // Validasi input
            $request->validate([
                'status' => 'required|in:active,inactive',
                'role_id' => 'required|numeric|exists:roles,id',
            ]);

            // Ambil user yang ingin diubah
            $user = User::findOrFail($userId);

            // Cegah owner mengubah dirinya sendiri
            if ($user->role_id == 1 && Auth::id() === $user->id) {
                throw new \Exception('Sebagai Owner, Anda tidak dapat mengubah diri Anda sendiri.');
            }

            // Ubah status
            $user->deleted_at = $request->status === 'inactive' ? now() : null;

            // Ubah role
            $user->role_id = $request->role_id;

            $user->save();

            return back()->with('success', 'User berhasil diupdate.');
        } catch (\Throwable $th) {
            return back()->with('failed', $th->getMessage());
        }
    }

}
