<?php

namespace App\Http\Controllers\Book;

use Exception;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Models\Book\Curriculum;
use App\Http\Controllers\Controller;
use App\Models\Book\Book;

class CurriculumController extends Controller
{
    public function index(Request $request)
    {
        $request->validate([
            'name' => 'nullable|string|max:255'
        ]);

        $curriculums = Curriculum::query()
            ->sortable()
            ->when($request->input('search'), function ($query, $search) {
                $query->where('name', 'like', "%{$search}%");
            })
            ->latest()
            ->orderBy('id', 'desc')
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

            $curriculum = Curriculum::findOrFail($curriculumId);

            // Cek apakah nama sudah digunakan oleh kurikulum lain
            $existingCurriculum = Curriculum::withTrashed()
                ->where('name', $request->name)
                ->where('id', '!=', $curriculumId)
                ->first();

            if ($existingCurriculum) {
                if ($existingCurriculum->deleted_at) {
                    // Jika ada kurikulum yang soft deleted dengan nama yang sama
                    // Pindahkan semua books ke kurikulum yang sudah ada (restore)
                    Book::where('curriculum_id', $curriculum->id)
                        ->update(['curriculum_id' => $existingCurriculum->id]);

                    // Restore kurikulum yang sudah ada
                    $existingCurriculum->restore();

                    // Hapus kurikulum yang sedang di-update
                    $curriculum->delete();

                    return back()->with('success', 'Kurikulum dan data yang berkaitan berhasil diupdate.');
                } else {
                    // Jika ada kurikulum aktif dengan nama yang sama
                    throw new Exception('Nama kurikulum sudah digunakan oleh kurikulum lain yang aktif.');
                }
            } else {
                // Jika tidak ada konflik nama, update normal
                $curriculum->update([
                    'name' => $request->name
                ]);

                return back()->with('success', 'Kurikulum berhasil diupdate');
            }
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

            // Cek apakah nama sudah digunakan (termasuk yang soft deleted)
            $existing = Curriculum::withTrashed()
                ->where('name', $request->name)
                ->first();

            if ($existing) {
                if ($existing->trashed()) {
                    // Restore dan update waktu
                    $existing->restore();
                    $existing->update([
                        'name' => $request->name,
                        'created_at' => now()
                    ]);
                } else {
                    // Sudah ada dan aktif
                    throw new \Exception('Nama kurikulum sudah digunakan.');
                }
            } else {
                // Data benar-benar baru
                Curriculum::create(['name' => $request->name]);
            }

            return back()->with('success', 'Kurikulum berhasil ditambahkan');
        } catch (\Throwable $th) {
            return back()->with('failed', 'Gagal menambah kurikulum: ' . $th->getMessage());
        }
    }
}
