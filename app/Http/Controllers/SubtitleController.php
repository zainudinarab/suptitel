<?php

namespace App\Http\Controllers;

use App\Models\SubtitleGroup;
use App\Models\SubtitleItem;
use App\Models\SubtitleLive;
use Illuminate\Http\Request;

class SubtitleController extends Controller
{
    public function index()
    {
        $groups = SubtitleGroup::withCount('items')->get();

        $live = SubtitleLive::with([
            'group',
            'item'
        ])->first();

        return view('subtitle.index', compact(
            'groups',
            'live'
        ));
    }

    public function group($id)
    {
        $group = SubtitleGroup::with('items')
            ->findOrFail($id);

        $live = SubtitleLive::first();

        return view('subtitle.group', [
            'group' => $group,
            'live' => $live
        ]);
    }

    public function storeGroup(Request $request)
    {
        SubtitleGroup::create([
            'nama' => $request->nama,
            'deskripsi' => $request->deskripsi
        ]);

        return back();
    }

    public function storeItem(Request $request)
    {
        SubtitleItem::create([
            'group_id' => $request->group_id,
            'judul' => $request->judul,
            'isi' => $request->isi,
            'urutan' => $request->urutan ?? 0
        ]);

        return back();
    }

    public function activateItem($id)
    {
        $item = SubtitleItem::findOrFail($id);

        $live = SubtitleLive::first();

        $live->update([
            'group_id' => $item->group_id,
            'subtitle_item_id' => $item->id,
            'current_word' => 0
        ]);

        return back();
    }

    public function nextSubtitle()
    {
        $live = SubtitleLive::first();

        if (!$live->subtitle_item_id) {
            return back();
        }

        $current = SubtitleItem::find(
            $live->subtitle_item_id
        );

        $next = SubtitleItem::where(
            'group_id',
            $current->group_id
        )
            ->where(
                'urutan',
                '>',
                $current->urutan
            )
            ->orderBy('urutan')
            ->first();

        if ($next) {
            $live->update([
                'subtitle_item_id' => $next->id,
                'current_word' => 0
            ]);
        }

        return back();
    }

    public function prevSubtitle()
    {
        $live = SubtitleLive::first();

        if (!$live->subtitle_item_id) {
            return back();
        }

        $current = SubtitleItem::find(
            $live->subtitle_item_id
        );

        $prev = SubtitleItem::where(
            'group_id',
            $current->group_id
        )
            ->where(
                'urutan',
                '<',
                $current->urutan
            )
            ->orderByDesc('urutan')
            ->first();

        if ($prev) {
            $live->update([
                'subtitle_item_id' => $prev->id,
                'current_word' => 0
            ]);
        }

        return back();
    }

    public function nextWord()
    {
        $live = SubtitleLive::first();

        if (!$live->item) {
            return response()->json([
                'success' => false
            ]);
        }

        $words = preg_split(
            '/\s+/u',
            trim($live->item->isi)
        );

        if (
            $live->current_word
            < count($words) - 1
        ) {
            $live->increment(
                'current_word'
            );
        } else {
            // Jika kata habis, otomatis pindah ke subtitle berikutnya
            $next = SubtitleItem::where('group_id', $live->group_id)
                ->where('urutan', '>', $live->item->urutan)
                ->orderBy('urutan')
                ->first();

            if ($next) {
                $live->update([
                    'subtitle_item_id' => $next->id,
                    'current_word' => 0
                ]);
            }
        }

        return response()->json([
            'success' => true
        ]);
    }

    public function prevWord()
    {
        $live = SubtitleLive::first();

        if (
            $live->current_word > 0
        ) {
            $live->decrement(
                'current_word'
            );
        } else {
            // Jika di kata pertama, kembali ke ayat sebelumnya (di kata terakhirnya)
            $prev = SubtitleItem::where('group_id', $live->group_id)
                ->where('urutan', '<', $live->item->urutan)
                ->orderByDesc('urutan')
                ->first();

            if ($prev) {
                $words = preg_split('/\s+/u', trim($prev->isi));
                $live->update([
                    'subtitle_item_id' => $prev->id,
                    'current_word' => count($words) - 1
                ]);
            }
        }

        return response()->json([
            'success' => true
        ]);
    }

    public function output()
    {
        $live = SubtitleLive::with([
            'group',
            'item'
        ])->first();

        return view(
            'subtitle.output',
            compact('live')
        );
    }

    public function getLiveJson()
    {
        $live = SubtitleLive::with(['item'])->first();
        return response()->json([
            'words' => $live?->words ?? [],
            'current_word' => $live?->current_word ?? 0,
            'has_item' => (bool)$live?->item
        ]);
    }
}
