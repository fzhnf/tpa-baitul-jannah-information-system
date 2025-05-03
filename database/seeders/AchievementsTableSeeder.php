<?php

namespace Database\Seeders;

use App\Models\Achievement;
use Illuminate\Database\Seeder;

class AchievementsTableSeeder extends Seeder
{
    public function run(): void
    {
        $csvFile = fopen(base_path('database/data/The Quran Dataset_trimmed.csv'), 'r');
        setlocale(LC_ALL, 'en_US.UTF-8');

        fgetcsv($csvFile);

        while (($data = fgetcsv($csvFile)) !== false) {
            $surahNo = $data[0];
            $surahName = $data[1];
            $ayahNo = $data[2];
            $juzNo = $data[3];

            $achievementName = "Q.S. {$surahName}/{$surahNo}: {$ayahNo} (Juz {$juzNo})";

            Achievement::create([
                'achievement_name' => $achievementName,
                'category' => 'tahfidz',
            ]);
        }

        fclose($csvFile);
    }
}
