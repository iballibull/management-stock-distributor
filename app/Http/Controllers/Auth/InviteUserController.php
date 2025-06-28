<?php

namespace App\Http\Controllers\Auth;

use App\Models\Role;
use App\Models\Invite;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
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
            $request->validate([
                'email' => ['required', 'email', 'unique:users,email', 'unique:invites,email'],
                'role_id' => ['required', 'exists:roles,id'],
            ], [
                'email.unique' => 'Email sudah digunakan atau sudah dikirimi undangan.',
            ]);

            $token = Str::uuid();

            $invite = Invite::create([
                'email' => $request->email,
                'role_id' => $request->role_id,
                'token' => $token,
            ]);

            Notification::route('mail', $invite->email)
                ->notify(new InviteUserNotification($token));

            return redirect()->back()->with('success', 'Undangan telah dikirim.');
        } catch (\Throwable $th) {
            return redirect()->back()->with('failed', $th->getMessage());
        }
    }

}
