<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * @mixin Builder
 * @mixin \Illuminate\Database\Query\Builder
 * @property int id
 * @property int user_id
 * @property int message
 * @property string created_at
 * @property string updated_at
 *
 * @property User chat_user
 */
class ChatMessage extends Model
{
    use HasFactory;

    protected $table = 'chat_messages';

    public function chat_user() : BelongsTo {
        return $this->belongsTo('App\Models\User','user_id','id');
    }
}
