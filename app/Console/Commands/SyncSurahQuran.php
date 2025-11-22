<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;

class SyncSurahQuran extends Command
{
    protected $signature = 'quran:sync-surah';
    protected $description = 'Ambil daftar surah dari API dan simpan ke file JSON lokal';

    public function handle()
    {
        $this->info("Mengambil data dari API...");

        try {
            $response = Http::get('https://api.alquran.cloud/v1/surah');

            if ($response->failed()) {
                $this->error("Gagal mengambil data dari API.");
                return;
            }

            $data = $response->json();

            Storage::disk('local')->put('quran/surah.json', json_encode($data['data'], JSON_PRETTY_PRINT));

            $this->info("Berhasil! Data surah disimpan ke storage/app/quran/surah.json");
        } catch (\Exception $e) {
            $this->error("❌ Error: " . $e->getMessage());
        }
    }
}
