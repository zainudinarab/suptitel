<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SubtitleLive extends Model
{
    protected $table = 'subtitle_live';

    protected $fillable = [
        'group_id',
        'subtitle_item_id',
        'current_word'
    ];

    public function group()
    {
        return $this->belongsTo(
            SubtitleGroup::class,
            'group_id'
        );
    }

    public function item()
    {
        return $this->belongsTo(
            SubtitleItem::class,
            'subtitle_item_id'
        );
    }

    /**
     * Pecah isi subtitle menjadi array kata
     */
    public function getWordsAttribute()
    {
        if (!$this->item) {
            return [];
        }
        return preg_split('/\s+/u', trim($this->item->isi));
    }
}
