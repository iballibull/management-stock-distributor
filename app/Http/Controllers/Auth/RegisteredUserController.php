<?php

namespace App\Http\Controllers\Auth;

use App\Models\User;
use App\Models\Invite;
use Illuminate\View\View;
use Illuminate\Http\Request;
use Illuminate\Validation\Rules;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Http\RedirectResponse;
use Illuminate\Auth\Events\Registered;

class RegisteredUserController extends Controller
{
    /**
     * Display the registration view.
     */
    public function create(Request $request): View
    {
        // Tangkap token dan cari undangan
        $invite = Invite::where('token', $request->token)->where('used', false)->first();

        if (!$invite) {
            abort(403, 'Token tidak valid.');
        }

        return view('auth.register', [
            'invite' => $invite,
        ]);
    }

    /**
     * Handle an incoming registration request.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request, $token): RedirectResponse
    {
        try {
            $request->validate([
                'name' => 'required|string|max:255',
                'password' => ['required', 'confirmed', Rules\Password::defaults()],
            ]);

            $invite = Invite::where('token', $token)
                ->where('used', false)
                ->firstOrFail();

            $user = User::create([
                'name' => $request->name,
                'email' => $invite->email,
                'password' => Hash::make($request->password),
                'role_id' => $invite->role_id,
            ]);

            $invite->update(['used' => true]);

            event(new Registered($user));
            Auth::login($user);

            return redirect(route('dashboard', absolute: false));
        } catch (\Throwable $th) {
            return back()->with('failed', $th->getMessage());
        }
    }
}
