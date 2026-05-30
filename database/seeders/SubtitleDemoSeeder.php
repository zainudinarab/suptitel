<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\SubtitleGroup;
use App\Models\SubtitleItem;

class SubtitleDemoSeeder extends Seeder
{
    public function run(): void
    {
        $group = SubtitleGroup::create([
            'nama' => 'Al-Fatihah'
        ]);

        SubtitleItem::insert([
            [
                'group_id' => $group->id,
                'judul' => 'Ayat 1',
                'isi' => 'بِسْمِ اللَّهِ الرَّحْمَٰنِ الرَّحِيمِ',
                'urutan' => 1,
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'group_id' => $group->id,
                'judul' => 'Ayat 2',
                'isi' => 'الْحَمْدُ لِلَّهِ رَبِّ الْعَالَمِينَ',
                'urutan' => 2,
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'group_id' => $group->id,
                'judul' => 'Ayat 3',
                'isi' => 'الرَّحْمَٰنِ الرَّحِيمِ',
                'urutan' => 3,
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'group_id' => $group->id,
                'judul' => 'Ayat 4',
                'isi' => 'مَالِكِ يَوْمِ الدِّينِ',
                'urutan' => 4,
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'group_id' => $group->id,
                'judul' => 'Ayat 5',
                'isi' => 'إِيَّاكَ نَعْبُدُ وَإِيَّاكَ نَسْتَعِينُ',
                'urutan' => 5,
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'group_id' => $group->id,
                'judul' => 'Ayat 6',
                'isi' => 'اهْدِنَا الصِّرَاطَ الْمُسْتَقِيمَ',
                'urutan' => 6,
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'group_id' => $group->id,
                'judul' => 'Ayat 7',
                'isi' => 'صِرَاطَ الَّذِينَ أَنْعَمْتَ عَلَيْهِمْ غَيْرِ الْمَغْضُوبِ عَلَيْهِمْ وَلَا الضَّالِّينَ',
                'urutan' => 7,
                'created_at' => now(),
                'updated_at' => now()
            ]
        ]);
    }
}
