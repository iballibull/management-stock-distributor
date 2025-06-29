<?php

namespace App\Http\Controllers\Auth;

use App\Models\User\Role;
use App\Models\User\Invite;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Notification;
use App\Notifications\InviteUserNotification;

class InviteUserController extends Controller
{
    public function create()
    {
        $roles = Role::pluck('name', 'id');

        return view('auth.create', compact('roles'));
    }

    public function store(Request $request)
    {
        try {
            $validated = $request->validate([
                'email' => [
                    'required',
                    'email',
                    Rule::unique('users', 'email')->whereNull('deleted_at'),
                ],
                'role_id' => ['required', 'exists:roles,id'],
            ], [
                'email.unique' => 'Email sudah digunakan.',
            ]);

            $token = Str::uuid();

            // Ketika ada user yang sebelumnya dihapus kemudian mendaftar lagi maka akan mengupdate data invite user tersebut
            // Jika tidak ada user sebelumnya maka data invite akan di buat
            $invite = Invite::updateOrCreate(
                ['email' => $validated['email']],
                [
                    'role_id' => $validated['role_id'],
                    'token' => $token,
                    'used' => false,
                ]
            );

            // Kirim notifikasi email
            Notification::route('mail', $invite->email)
                ->notify(new InviteUserNotification($token));

            return redirect()->back()->with('success', 'Undangan telah dikirim.');
        } catch (\Throwable $th) {
            return redirect()->back()->with('failed', $th->getMessage());
        }
    }

}
