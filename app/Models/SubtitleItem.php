<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SubtitleItem extends Model
{
    protected $fillable = [
        'group_id',
        'judul',
        'isi',
        'urutan'
    ];

    public function group()
    {
        return $this->belongsTo(
            SubtitleGroup::class,
            'group_id'
        );
    }
}
