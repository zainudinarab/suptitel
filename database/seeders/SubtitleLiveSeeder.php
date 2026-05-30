<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\SubtitleLive;

class SubtitleLiveSeeder extends Seeder
{
    public function run(): void
    {
        if (!SubtitleLive::exists()) {

            SubtitleLive::create([
                'group_id' => null,
                'subtitle_item_id' => null,
                'current_word' => 0
            ]);
        }
    }
}
