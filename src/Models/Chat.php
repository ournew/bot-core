<?php

namespace OurNew\BotCore\Models;

use Illuminate\Database\Eloquent\Model;
use SergiX44\Nutgram\Telegram\Types\User\User;

class Chat extends Model
{
    protected $primaryKey = 'chat_id';
    public $incrementing = false;
    protected static $unguarded = true;
    
    protected $casts = [
        'started_at' => 'datetime',
        'blocked_at' => 'datetime',
    ];
    
    public static function findFromUser(?User $user): ?Chat
    {
        if ($user === null) {
            return null;
        }
        
        $chat = self::find($user->id);
        
        return $chat ?? null;
    }
}