<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DownloadLog extends Model
{
    protected $fillable = [
        'mod_id',
        'user_id',
        'ip_address',
        'user_agent',
    ];

    public function mod()
    {
        return $this->belongsTo(Mod::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
