<?php

namespace App\Http\Controllers\BookTransaction;

use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use App\Http\Controllers\Controller;
use App\Models\BookTransaction\Semester;

class SemesterController extends Controller
{
    public function index(Request $request)
    {
        // Validasi input
        $request->validate([
            'name' => 'nullable|string|max:255'
        ]);

        // Awal query
        $query = Semester::sortable();

        // Filter by name
        if ($request->name) {
            $query->where('name', 'like', '%' . $request->name . '%');
        }

        // Urutkan & paginate
        $semesters = $query
            ->latest()
            ->paginate(10)
            ->withQueryString();

        return view('book-transaction.semester-index', compact('semesters'));
    }

    public function destroy($semesterId)
    {
        // Temukan semester berdasarkan ID
        $semester = Semester::findOrFail($semesterId);

        // Hapus semester
        $semester->delete();

        // Redirect dengan pesan sukses
        return redirect()->route('semester.index')->with('success', 'Semester berhasil dihapus.');
    }

    public function store(Request $request)
    {
        try {
            $request->validate([
                'year' => 'required|integer|min:2020|max:2030',
                'semester_number' => 'required|in:1,2',
            ]);

            $year = $request->year;
            $semesterNumber = $request->semester_number;

            if ($semesterNumber == 1) {
                $startDate = Carbon::create($year, 1, 1);
                $endDate = Carbon::create($year, 6, 30);
            } else {
                $startDate = Carbon::create($year, 7, 1);
                $endDate = Carbon::create($year, 12, 31);
            }

            $semester = Semester::withTrashed()
                ->where('year', $year)
                ->where('semester_number', $semesterNumber)
                ->first();

            if ($semester) {
                if ($semester->deleted_at) {
                    // Jika semester sudah dihapus, pulihkan
                    $semester->created_at = now();
                    $semester->restore();
                    return redirect()->route('semester.index')->with('success', 'Semester berhasil ditambahkan');
                } else {
                    // Jika semester sudah ada dan belum dihapus, tampilkan pesan error
                    throw new \Exception('Semester untuk tahun ' . $year . ' dan semester ' . $semesterNumber . ' sudah ada.');
                }
            } else {
                Semester::create([
                    'name' => 'SEMESTER ' . $semesterNumber . ' ' . $year,
                    'year' => $year,
                    'semester_number' => $semesterNumber,
                    'start_date' => $startDate->format('Y-m-d'),
                    'end_date' => $endDate->format('Y-m-d'),
                ]);
            }

            return redirect()->route('semester.index')->with('success', 'Semester berhasil ditambahkan');
        } catch (\Exception $e) {
            return redirect()->back()->with('failed', 'Semester gagal ditambahkan: ' . $e->getMessage());
        }
    }

    public function update(Request $request, $semesterId)
    {
        try {
            // Validasi input
            $request->validate([
                'year' => 'required|integer|min:2020|max:2030',
                'semester_number' => 'required|in:1,2',
            ]);

            // Temukan semester berdasarkan ID
            $semester = Semester::findOrFail($semesterId);

            // Ambil data dari request
            $year = $request->year;
            $semesterNumber = $request->semester_number;

            // Tentukan tanggal mulai dan akhir semester
            if ($semesterNumber == 1) {
                $startDate = Carbon::create($year, 1, 1);
                $endDate = Carbon::create($year, 6, 30);
            } else {
                $startDate = Carbon::create($year, 7, 1);
                $endDate = Carbon::create($year, 12, 31);
            }

            // Cek apakah semester dengan tahun dan semester_number yang sama sudah ada di trash
            $existingSemester = Semester::withTrashed()
                ->where('id', '!=', $semesterId)
                ->where('year', $year)
                ->where('semester_number', $semesterNumber)
                ->whereNotNull('deleted_at')
                ->first();

            if ($existingSemester && $existingSemester->id !== $semesterId) {
                // Restore existing semester dengan data baru
                $existingSemester->restore();
                $existingSemester->update([
                    'name' => 'SEMESTER ' . $semesterNumber . ' ' . $year,
                    'year' => $year,
                    'semester_number' => $semesterNumber,
                    'start_date' => $startDate->format('Y-m-d'),
                    'end_date' => $endDate->format('Y-m-d'),
                    'created_at' => $semester->created_at,
                ]);

                // Update data yang berelasi dengan semester ini
                // TODO: Update semua data yang menggunakan $semesterId menjadi $existingSemester->id

                $semester->delete();

                return redirect()->route('semester.index')->with('success', 'Semester berhasil diperbarui');
            }

            // Cek apakah semester dengan kriteria yang sama sudah ada (tidak di trash)
            $duplicateSemester = Semester::where('id', '!=', $semesterId)
                ->where('year', $year)
                ->where('semester_number', $semesterNumber)
                ->whereNull('deleted_at')
                ->first();

            if ($duplicateSemester) {
                throw new \Exception('Semester untuk tahun ' . $year . ' dan semester ' . $semesterNumber . ' sudah ada.');
            }

            // Update semester biasa
            $semester->update([
                'name' => 'SEMESTER ' . $semesterNumber . ' ' . $year,
                'year' => $year,
                'semester_number' => $semesterNumber,
                'start_date' => $startDate->format('Y-m-d'),
                'end_date' => $endDate->format('Y-m-d'),
            ]);

            return redirect()->route('semester.index')->with('success', 'Semester berhasil diperbarui');

        } catch (\Exception $e) {
            return redirect()->back()->with('failed', 'Semester gagal diperbarui: ' . $e->getMessage());
        }
    }

}
