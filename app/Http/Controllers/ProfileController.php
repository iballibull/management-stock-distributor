<?php

namespace App\Http\Controllers;

use Illuminate\View\View;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Redirect;
use App\Http\Requests\Pofile\ProfileUpdateRequest;
use Str;

class ProfileController extends Controller
{
    /**
     * Display the user's profile form.
     */
    public function edit(Request $request): View
    {
        return view('profile.edit', [
            'user' => $request->user(),
        ]);
    }

    /**
     * Update the user's profile information.
     */
    public function update(ProfileUpdateRequest $request): RedirectResponse
    {
        try {
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

            return Redirect::route('profile.edit')->with('success', 'Profile berhasil di update');
        } catch (\Throwable $th) {
            return Redirect::route('profile.edit')->with('failed', $th->getMessage());
        }
    }


    /**
     * Delete the user's account.
     */
    public function destroy(Request $request): RedirectResponse
    {
        $request->validateWithBag('userDeletion', [
            'password' => ['required', 'current_password'],
        ]);

        $user = $request->user();

        Auth::logout();

        $user->delete();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return Redirect::to('/');
    }
}
