<?php

namespace App\Http\Controllers\Book;

use Exception;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Models\Book\EducationLevel;
use App\Http\Controllers\Controller;

class EducationLevelController extends Controller
{
    public function index(Request $request)
    {
        $request->validate([
            'name' => 'nullable|string|max:255'
        ]);

        $educationLevels = EducationLevel::query()
            ->sortable()
            ->when($request->input('search'), function ($query, $search) {
                $query->where('name', 'like', "%{$search}%");
            })->latest()
            ->paginate(10)->appends($request->query());

        return view('book.education-level', compact('educationLevels'));
    }

    public function update(Request $request, $educationLevelId)
    {
        try {
            $request->validate([
                'name' => 'required|string|max:255'
            ]);

            $upperName = Str::upper($request->input('name'));

            $educationLevel = EducationLevel::findOrFail($educationLevelId);

            // Cek apakah nama sudah digunakan oleh tingkat pendidikan lain
            $alreadyUsed = EducationLevel::withTrashed()
                ->where('name', $upperName)
                ->where('id', '!=', $educationLevelId)
                ->first();

            if ($alreadyUsed) {
                throw new Exception('Nama tingkat pendidikan sudah digunakan.');
            }

            $educationLevel->update([
                'name' => $upperName
            ]);

            return back()->with('success', 'Tingkat pendidikan berhasil diupdate');
        } catch (\Throwable $th) {
            return back()->with('failed', 'Gagal mengupdate tingkat pendidikan: ' . $th->getMessage());
        }
    }

    public function store(Request $request)
    {
        try {
            $request->validate([
                'name' => 'required|string|max:255'
            ]);

            $upperName = Str::upper($request->input('name'));

            // Cek apakah nama sudah digunakan (termasuk yang soft deleted)
            $existing = EducationLevel::withTrashed()
                ->where('name', $upperName)
                ->first();

            if ($existing) {
                if ($existing->trashed()) {
                    // Restore dan update waktu
                    $existing->restore();
                    $existing->update([
                        'name' => $upperName,
                        'created_at' => now()
                    ]);
                } else {
                    // Sudah ada dan aktif
                    throw new Exception('Nama tingkat pendidikan sudah digunakan.');
                }
            } else {
                // Data benar-benar baru
                EducationLevel::create(['name' => $upperName]);
            }

            return back()->with('success', 'Tingkat pendidikan berhasil ditambahkan');
        } catch (\Throwable $th) {
            return back()->with('failed', 'Gagal menambah tingkat pendidikan: ' . $th->getMessage());
        }
    }

    public function destroy($curriculumId)
    {
        try {
            $category = EducationLevel::findOrFail($curriculumId);
            $category->delete();

            return back()->with('success', 'Tingkat pendidikan berhasil dihapus');
        } catch (\Throwable $th) {
            return back()->with('failed', 'Gagal menghapus tingkat pendidikan: ' . $th->getMessage());
        }
    }
}
