<?php

namespace App\Http\Controllers\Book;

use Exception;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Models\Book\Curriculum;
use App\Http\Controllers\Controller;

class CurriculumController extends Controller
{
    public function index(Request $request)
    {
        $request->validate([
            'name' => 'nullable|string|max:255'
        ]);

        $curriculums = Curriculum::query()
            ->when($request->input('search'), function ($query, $search) {
                $query->where('name', 'like', "%{$search}%");
            })->latest()
            ->paginate(10)->appends($request->query());

        return view('book.curriculum', compact('curriculums'));
    }

    public function destroy($curriculumId)
    {
        try {
            $category = Curriculum::findOrFail($curriculumId);
            $category->delete();

            return back()->with('success', 'Kurikulum berhasil dihapus');
        } catch (\Throwable $th) {
            return back()->with('failed', 'Gagal menghapus kurikulum: ' . $th->getMessage());
        }
    }

    public function update(Request $request, $curriculumId)
    {
        try {
            $request->validate([
                'name' => 'required|string|max:255'
            ]);

            $upperName = Str::upper($request->input('name'));

            $curriculum = Curriculum::findOrFail($curriculumId);

            // Cek apakah nama sudah digunakan oleh kurikulum lain
            $alreadyUsed = Curriculum::withTrashed()
                ->where('name', $upperName)
                ->where('id', '!=', $curriculumId)
                ->first();

            if ($alreadyUsed) {
                throw new Exception('Nama kurikulum sudah digunakan.');
            }

            $curriculum->update([
                'name' => $upperName
            ]);

            return back()->with('success', 'Kurikulum berhasil diupdate');
        } catch (\Throwable $th) {
            return back()->with('failed', 'Gagal mengupdate kurikulum: ' . $th->getMessage());
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
            $existing = Curriculum::withTrashed()
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
                    throw new \Exception('Nama kurikulum sudah digunakan.');
                }
            } else {
                // Data benar-benar baru
                Curriculum::create(['name' => $upperName]);
            }

            return back()->with('success', 'Kurikulum berhasil ditambahkan');
        } catch (\Throwable $th) {
            return back()->with('failed', 'Gagal menambah kurikulum: ' . $th->getMessage());
        }
    }
}
