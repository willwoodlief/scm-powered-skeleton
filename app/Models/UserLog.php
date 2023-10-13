<?php

namespace App\Models;

use App\Helpers\Utilities;
use App\Plugins\Plugin;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use TorMorten\Eventy\Facades\Eventy;

/**
 * @mixin Builder
 * @mixin \Illuminate\Database\Query\Builder
 * @property int id
 * @property int user_id
 * @property string action
 * @property string created_at
 * @property string updated_at
 *
 * @property User log_user
 */
class UserLog extends Model
{
    use HasFactory;

    protected $table = 'logs';

    public function log_user() : BelongsTo {
        return $this->belongsTo('App\Models\User','user_id','id');
    }

    protected static function booted(): void
    {
        static::creating(function (UserLog $user_log) {
            Eventy::action(Plugin::ACTION_MODEL_CREATING.Plugin::ACTION_MODEL_NAME_USER_LOG, $user_log);
        });

        static::created(function (UserLog $user_log) {
            Eventy::action(Plugin::ACTION_MODEL_CREATED.Plugin::ACTION_MODEL_NAME_USER_LOG, $user_log);
        });

        static::updating(function (UserLog $user_log) {
            Eventy::action(Plugin::ACTION_MODEL_UPDATING.Plugin::ACTION_MODEL_NAME_USER_LOG, $user_log);
        });

        static::updated(function (UserLog $user_log) {
            Eventy::action(Plugin::ACTION_MODEL_UPDATED.Plugin::ACTION_MODEL_NAME_USER_LOG, $user_log);
        });

        static::deleted(function (UserLog $user_log) {
            Eventy::action(Plugin::ACTION_MODEL_DELETED.Plugin::ACTION_MODEL_NAME_USER_LOG, $user_log);
        });

        static::deleting(function (UserLog $user_log) {
            Eventy::action(Plugin::ACTION_MODEL_DELETING.Plugin::ACTION_MODEL_NAME_USER_LOG, $user_log);
        });
    }

    /**
     * @param int|null $how_many
     * @return UserLog[][]|\Illuminate\Database\Eloquent\Collection
     */
    public static function getLastLogs(?int $how_many = null) {

        $query = function() use($how_many): Builder {
            $query = UserLog::where('id','>',0)
                /**
                 * @uses UserLog::log_user()
                 */
                ->with('log_user')
                ->orderBy('id','desc');

            if ($how_many && $how_many > 0) {
                $query = $query->limit($how_many);
            }
            return Eventy::filter(Plugin::FILTER_QUERY_LAST_USER_LOGS, $query);
        };

        return  $query()->get();

    }

    public static function addLog(string $action) : UserLog {
        $node = new UserLog();
        $user = Utilities::get_logged_user();
        $node->user_id = $user->id;
        $node->action = $action;
        $node->save();
        return $node;
    }
}
