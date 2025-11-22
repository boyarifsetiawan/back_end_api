<?php

namespace App\Services;

use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Http;

class GeminiServices
{
    protected $apiKey;
    protected $model = 'gemini-2.5-flash'; // Sesuaikan dengan model yang Anda gunakan

    public function __construct()
    {
        // Ambil kunci API dari .env
        $this->apiKey = env('GEMINI_API_KEY');
        if (empty($this->apiKey)) {
            throw new \Exception("GEMINI_API_KEY not set in .env file.");
        }
    }



    public function generateContent(string $prompt): string
    {
        $url = "https://generativelanguage.googleapis.com/v1/models/{$this->model}:generateContent?key={$this->apiKey}";
        // Tambahkan timeout 60 detik (opsional)
        // Tambahkan fitur retry: coba 3 kali, jeda 100 milidetik antar percobaan,
        // dan hanya retry untuk status 503 dan 504
        try {
            $response = Http::timeout(60)
                ->retry(3, 100)->post($url, [
                    'contents' => [
                        [
                            'parts' => [
                                ['text' => $prompt]
                            ]
                        ]
                    ],
                ]);

            if ($response->failed()) {
                Log::error('Gemini API Request Failed', ['response' => $response->json()]);
                return "Error: Gagal menghubungi AI.";
            }
            $data = $response->json();
            $text = $data['candidates'][0]['content']['parts'][0]['text'] ?? 'Tidak ada hasil yang ditemukan.';

            return $text;
        } catch (\Exception $e) {
            Log::error('Gemini API Exception', ['message' => $e->getMessage()]);
            return response()->json(['message' => 'Server Sibuk'], 500);
        }
    }
}
