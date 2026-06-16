<?php

namespace App\Http\Controllers;

use App\Models\BankSoal;
use App\Models\MataPelajaran;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use OpenAI\Laravel\Facades\OpenAI;

class AiGenerateController extends Controller
{
    public function index()
    {
        $mapelList = MataPelajaran::where('guru_id', Auth::id())->get();
        if ($mapelList->isEmpty()) {
            $mapelList = MataPelajaran::all();
        }
        
        return view('ai-generate.index', compact('mapelList'));
    }

    public function generate(Request $request)
    {
        $request->validate([
            'mapel_id' => 'required|exists:mata_pelajarans,id',
            'topik' => 'required|string|max:500',
            'jumlah' => 'required|integer|min:1|max:20',
            'tipe' => 'required|in:pg,essay,campuran',
            'level' => 'required|in:mudah,sedang,sulit,campuran',
        ]);

        $mapel = MataPelajaran::findOrFail($request->mapel_id);
        
        // Build prompt berdasarkan parameter
        $prompt = $this->buildPrompt(
            $mapel->nama,
            $request->topik,
            $request->jumlah,
            $request->tipe,
            $request->level
        );

        try {
            // Call OpenAI API
            $response = OpenAI::chat()->create([
                'model' => 'gpt-3.5-turbo',
                'messages' => [
                    ['role' => 'system', 'content' => 'Anda adalah guru ahli yang membuat soal ujian. Jawab HANYA dalam format JSON yang valid.'],
                    ['role' => 'user', 'content' => $prompt]
                ],
                'temperature' => 0.7,
                'max_tokens' => 2000,
            ]);

            $content = $response->choices[0]->message->content;
            
            // Parse JSON response
            $soalList = $this->parseResponse($content);

            return response()->json([
                'success' => true,
                'soal' => $soalList,
                'mapel_id' => $request->mapel_id,
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'error' => 'Gagal generate soal: ' . $e->getMessage()
            ], 500);
        }
    }

    public function save(Request $request)
    {
        $request->validate([
            'soal' => 'required|array',
            'mapel_id' => 'required|exists:mata_pelajarans,id',
        ]);

        $saved = 0;
        foreach ($request->soal as $item) {
            BankSoal::create([
                'guru_id' => Auth::id(),
                'mapel_id' => $request->mapel_id,
                'tipe' => $item['tipe'],
                'pertanyaan' => $item['pertanyaan'],
                'opsi_a' => $item['opsi_a'] ?? null,
                'opsi_b' => $item['opsi_b'] ?? null,
                'opsi_c' => $item['opsi_c'] ?? null,
                'opsi_d' => $item['opsi_d'] ?? null,
                'opsi_e' => $item['opsi_e'] ?? null,
                'jawaban' => $item['jawaban'],
                'level' => $item['level'],
                'status' => 'draft', // Simpan sebagai draft dulu
            ]);
            $saved++;
        }

        return redirect()->route('bank-soal.index')
            ->with('success', "{$saved} soal berhasil disimpan ke Bank Soal (status: draft)");
    }

    private function buildPrompt($mapel, $topik, $jumlah, $tipe, $level)
    {
        $tipeText = match($tipe) {
            'pg' => 'pilihan ganda dengan 5 opsi (A-E)',
            'essay' => 'essay/uraian',
            'campuran' => 'campuran pilihan ganda dan essay (70% PG, 30% essay)',
        };

        $levelText = match($level) {
            'mudah' => 'mudah (untuk pemula)',
            'sedang' => 'sedang (untuk siswa rata-rata)',
            'sulit' => 'sulit (untuk siswa advanced)',
            'campuran' => 'campuran (30% mudah, 50% sedang, 20% sulit)',
        };

        return "Buatkan {$jumlah} soal {$tipeText} untuk mata pelajaran {$mapel} tentang topik: {$topik}. Level kesulitan: {$levelText}.

Format jawaban HARUS dalam JSON array seperti ini:
[
  {
    \"tipe\": \"pg\" atau \"essay\",
    \"pertanyaan\": \"teks pertanyaan\",
    \"opsi_a\": \"opsi A (jika pg)\",
    \"opsi_b\": \"opsi B (jika pg)\",
    \"opsi_c\": \"opsi C (jika pg)\",
    \"opsi_d\": \"opsi D (jika pg)\",
    \"opsi_e\": \"opsi E (jika pg, opsional)\",
    \"jawaban\": \"A/B/C/D/E (jika pg) atau kunci jawaban (jika essay)\",
    \"level\": \"mudah/sedang/sulit\"
  }
]

Hanya return JSON, jangan ada teks lain.";
    }

    private function parseResponse($content)
    {
        // Extract JSON from response
        if (preg_match('/\[[\s\S]*\]/', $content, $matches)) {
            $json = $matches[0];
            $data = json_decode($json, true);
            
            if (json_last_error() === JSON_ERROR_NONE) {
                return $data;
            }
        }

        throw new \Exception('Format response AI tidak valid');
    }
}