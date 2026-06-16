<?php

namespace App\Http\Controllers;

use App\Models\BankSoal;
use App\Models\MataPelajaran;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

use App\Imports\SoalImport;
use Maatwebsite\Excel\Facades\Excel;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

class BankSoalController extends Controller
{
    public function index(Request $request)
    {
        $query = BankSoal::where('guru_id', Auth::id())
            ->with('mapel');

        if ($request->filled('mapel_id')) {
            $query->where('mapel_id', $request->mapel_id);
        }

        if ($request->filled('tipe')) {
            $query->where('tipe', $request->tipe);
        }

        if ($request->filled('level')) {
            $query->where('level', $request->level);
        }

        if ($request->filled('search')) {
            $query->where(
                'pertanyaan',
                'like',
                '%' . $request->search . '%'
            );
        }

        $soal = $query->latest()->paginate(12);

        $mapelList = MataPelajaran::where(
            'guru_id',
            Auth::id()
        )->get();

        $totalSoal = BankSoal::where(
            'guru_id',
            Auth::id()
        )->count();

        $soalPublished = BankSoal::where(
            'guru_id',
            Auth::id()
        )->where(
            'status',
            'published'
        )->count();

        $soalDraft = BankSoal::where(
            'guru_id',
            Auth::id()
        )->where(
            'status',
            'draft'
        )->count();

        return view(
            'bank-soal.index',
            compact(
                'soal',
                'mapelList',
                'totalSoal',
                'soalPublished',
                'soalDraft'
            )
        );
    }

    public function create()
    {
        $mapelList = MataPelajaran::where(
            'guru_id',
            Auth::id()
        )->get();

        if ($mapelList->isEmpty()) {
            $mapelList = MataPelajaran::all();
        }

        return view(
            'bank-soal.create',
            compact('mapelList')
        );
    }

  public function store(Request $request)
{
    $request->validate([
        'mapel_id' => 'required|exists:mata_pelajarans,id',
        'level' => 'required|in:mudah,sedang,sulit',
    ]);

    $created = 0;
    
    if ($request->has('soal_list') && is_array($request->soal_list)) {
        foreach ($request->soal_list as $soalData) {
            BankSoal::create([
                'guru_id' => Auth::id(),
                'mapel_id' => $request->mapel_id,
                'tipe' => $soalData['tipe'],
                'pertanyaan' => $soalData['pertanyaan'],
                'level' => $request->level,
                'status' => 'published',
                'opsi_a' => $soalData['opsi']['A'] ?? null,
                'opsi_b' => $soalData['opsi']['B'] ?? null,
                'opsi_c' => $soalData['opsi']['C'] ?? null,
                'opsi_d' => $soalData['opsi']['D'] ?? null,
                'jawaban' => $soalData['jawaban'] ?? null,
                'penjelasan' => $soalData['penjelasan'] ?? null,
                'opsi_a_tipe' => 'text',
                'opsi_b_tipe' => 'text',
                'opsi_c_tipe' => 'text',
                'opsi_d_tipe' => 'text',
            ]);
            $created++;
        }
    }

    return redirect()->route('bank-soal.index')
        ->with('success', "Berhasil menyimpan {$created} soal ke Bank Soal! 🎉");
}
    public function edit($id)
    {
        $soal = BankSoal::where(
            'guru_id',
            Auth::id()
        )->findOrFail($id);

        $mapelList = MataPelajaran::all();

        return view(
            'bank-soal.edit',
            compact(
                'soal',
                'mapelList'
            )
        );
    }

    public function update(
        Request $request,
        $id
    ) {
        $soal = BankSoal::where(
            'guru_id',
            Auth::id()
        )->findOrFail($id);

        $data = $request->except(
            'gambar_soal'
        );

        if ($request->hasFile('gambar_soal')) {

            if ($soal->gambar_soal) {
                Storage::disk('public')
                    ->delete(
                        $soal->gambar_soal
                    );
            }

            $data['gambar_soal'] =
                $request
                    ->file('gambar_soal')
                    ->store(
                        'soal-images',
                        'public'
                    );
        }

        $soal->update($data);

        return redirect()
            ->route('bank-soal.index')
            ->with(
                'success',
                'Soal berhasil diperbarui!'
            );
    }

    public function destroy($id)
    {
        $soal = BankSoal::where(
            'guru_id',
            Auth::id()
        )->findOrFail($id);

        if ($soal->gambar_soal) {
            Storage::disk('public')
                ->delete(
                    $soal->gambar_soal
                );
        }

        $soal->delete();

        return redirect()
            ->route('bank-soal.index')
            ->with(
                'success',
                'Soal berhasil dihapus!'
            );
    }

    /*
    |--------------------------------------------------------------------------
    | IMPORT EXCEL
    |--------------------------------------------------------------------------
    */

    public function import(Request $request)
    {
        $request->validate([
            'file' =>
                'required|mimes:xlsx,xls,csv|max:5120',
        ]);

        try {

            Excel::import(
                new SoalImport,
                $request->file('file')
            );

            $message =
                'Soal berhasil diimport!';

            return redirect()
                ->route('bank-soal.index')
                ->with(
                    'success',
                    $message
                );

        } catch (
            \Maatwebsite\Excel\Validators\ValidationException $e
        ) {

            $errors = [];

            foreach (
                $e->failures()
                as $failure
            ) {
                $errors[] =
                    "Baris {$failure->row()}: "
                    .
                    implode(
                        ', ',
                        $failure->errors()
                    );
            }

            return back()->with(
                'error',
                implode(
                    ' | ',
                    $errors
                )
            );
        }
    }

    /*
    |--------------------------------------------------------------------------
    | DOWNLOAD TEMPLATE
    |--------------------------------------------------------------------------
    */

    public function downloadTemplate()
    {
        $spreadsheet =
            new Spreadsheet();

        $sheet =
            $spreadsheet
            ->getActiveSheet();

        $header = [
            'Mapel',
            'Tipe',
            'Pertanyaan',
            'Opsi A',
            'Opsi B',
            'Opsi C',
            'Opsi D',
            'Opsi E',
            'Jawaban',
            'Level'
        ];

        foreach (
            $header
            as $i => $value
        ) {
            $sheet->setCellValue(
                chr(65 + $i) . '1',
                $value
            );
        }

        $sample = [
            'Matematika',
            'pg',
            'Berapa hasil dari 5 + 5?',
            '8',
            '9',
            '10',
            '11',
            '12',
            'C',
            'mudah'
        ];

        foreach (
            $sample
            as $i => $value
        ) {
            $sheet->setCellValue(
                chr(65 + $i) . '2',
                $value
            );
        }

        $writer =
            new Xlsx(
                $spreadsheet
            );

        return response()->stream(
            function () use ($writer) {
                $writer->save(
                    'php://output'
                );
            },
            200,
            [
                'Content-Type' =>
                    'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',

                'Content-Disposition' =>
                    'attachment; filename="Template_Import_Soal.xlsx"',
            ]
        );
    }
}

