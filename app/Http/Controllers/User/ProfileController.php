<?php

namespace App\Http\Controllers\User;

use Str;
use Illuminate\View\View;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Redirect;
use App\Http\Requests\Profile\ProfileUpdateRequest;

class ProfileController extends Controller
{
    /**
     * Display the user's profile form.
     */
    public function edit(Request $request): View
    {
        return view('user.edit', [
            'user' => $request->user(),
        ]);
    }

    /**
     * Update the user's profile information.
     */
    public function update(ProfileUpdateRequest $request): RedirectResponse
    {
        $user = $request->user();

        // Validasi seluruh input (termasuk validasi photo)
        $validated = $request->validated();

        // Handle file photo jika ada
        if ($request->hasFile('photo')) {
            $photo = $request->file('photo');

            // Hapus foto lama 
            if ($user->photo && Storage::disk('public')->exists($user->photo)) {
                Storage::disk('public')->delete($user->photo);
            }

            // Simpan file baru 
            $filename = now()->format('Ymd_His') . '_' . Str::uuid() . '.' . $photo->getClientOriginalExtension();
            $path = $photo->storeAs('photos', $filename, 'public');

            // Masukkan path foto ke data validated
            $validated['photo'] = $path;
        }

        // Assign data user
        $user->fill($validated);

        if ($user->isDirty('email')) {
            $user->email_verified_at = null;
        }

        // Simpan user
        $user->save();

        return Redirect::route('user.edit')->with('success', 'Profile berhasil di update');
    }

    public function updatePassword(Request $request)
    {
        $request->validate([
            'current_password' => ['required', 'current_password'],
            'password' => ['required', 'min:8', 'confirmed'],
        ]);

        // Update password logic here
        $request->user()->update([
            'password' => Hash::make($request->password)
        ]);

        return back()->with('success', 'Password berhasil diubah.');
    }
}
