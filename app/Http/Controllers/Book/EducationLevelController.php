<?php

namespace App\Http\Controllers\Book;

use Exception;
use App\Models\Book\Book;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
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
            })
            ->latest()
            ->orderBy('id', 'desc')
            ->paginate(10)->appends($request->query());

        return view('book.education-level', compact('educationLevels'));
    }

    public function update(Request $request, $educationLevelId)
    {
        try {
            $request->validate([
                'name' => 'required|string|max:255'
            ]);

            $educationLevel = EducationLevel::findOrFail($educationLevelId);

            // Cek apakah nama sudah digunakan oleh tingkat pendidikan lain
            $existingEducationLevel = EducationLevel::withTrashed()
                ->where('name', $request->name)
                ->where('id', '!=', $educationLevelId)
                ->first();

            if ($existingEducationLevel) {
                if ($existingEducationLevel->deleted_at) {
                    try {
                        DB::beginTransaction();
                        // Jika ada tingkat pendidikan yang soft deleted dengan nama yang sama
                        // Pindahkan semua books ke tingkat pendidikan yang sudah ada (restore)
                        Book::where('education_level_id', $educationLevel->id)
                            ->update(['education_level_id' => $existingEducationLevel->id]);

                        // Restore tingkat pendidikan yang sudah ada
                        $existingEducationLevel->restore();

                        // Hapus tingkat pendidikan yang sedang di-update
                        $educationLevel->delete();
                        DB::commit();

                        return back()->with('success', 'Tingkat pendidikan dan data yang berkaitan berhasil diupdate.');
                    } catch (\Throwable $th) {
                        DB::rollBack();
                        return back()->with('failed', 'Gagal mengupdate tingkat pendidikan: ' . $th->getMessage());
                    }
                } else {
                    // Jika ada tingkat pendidikan aktif dengan nama yang sama
                    throw new Exception('Nama tingkat pendidikan sudah digunakan oleh tingkat pendidikan lain yang aktif.');
                }
            } else {
                // Jika tidak ada konflik nama, update normal
                $educationLevel->update([
                    'name' => Str::upper($request->name),
                ]);

                return back()->with('success', 'Tingkat pendidikan berhasil diupdate');
            }
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
