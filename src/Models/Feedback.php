<?php

namespace OurNew\BotCore\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Carbon;

/**
 * App\Models\Feedback
 *
 * @property int $id
 * @property int $chat_id
 * @property string $message
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 *
 * @property-read Chat $chat
 *
 * @method static Builder|Feedback newModelQuery()
 * @method static Builder|Feedback newQuery()
 * @method static Builder|Feedback query()
 * @method static Builder|Feedback whereChatId($value)
 * @method static Builder|Feedback whereCreatedAt($value)
 * @method static Builder|Feedback whereId($value)
 * @method static Builder|Feedback whereMessage($value)
 * @method static Builder|Feedback whereUpdatedAt($value)
 */
class Feedback extends Model
{
    protected static $unguarded = true;
    
    public function chat(): BelongsTo
    {
        return $this->belongsTo(Chat::class);
    }
}
