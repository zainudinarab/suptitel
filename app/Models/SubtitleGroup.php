<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SubtitleGroup extends Model
{
    protected $fillable = [
        'nama',
        'deskripsi'
    ];

    public function items()
    {
        return $this->hasMany(
            SubtitleItem::class,
            'group_id'
        )->orderBy('urutan');
    }
}
