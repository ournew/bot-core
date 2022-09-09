<?php

namespace OurNew\BotCore\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Statistic extends Model
{
    public $timestamps = false;
    protected static $unguarded = true;
    
    protected $casts = [
        'value' => 'array',
        'collected_at' => 'datetime',
    ];
    
    public function chat(): BelongsTo
    {
        return $this->belongsTo(Chat::class, 'chat_id', 'chat_id');
    }
}