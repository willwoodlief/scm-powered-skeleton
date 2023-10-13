<?php

namespace App\Models;

use App\Exceptions\UserException;
use App\Plugins\Plugin;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use TorMorten\Eventy\Facades\Eventy;

/**
 * @mixin Builder
 * @mixin \Illuminate\Database\Query\Builder
 * @property int id
 * @property int user_id
 * @property string item
 * @property string date
 * @property string created_at
 * @property string updated_at
 */
class ToDo extends Model
{
    use HasFactory;

    protected $table = 'todo';

    protected $fillable = [
        'item',
    ];

    /**
     * @param int $user_id
     * @return ToDo[]|\Illuminate\Database\Eloquent\Collection|\Illuminate\Support\Collection
     */
    public static function getUserTodo(int $user_id) {

        $start_user = User::find($user_id);
        if (!$start_user) {
            throw new UserException(__("User not found while getting todo list"));

        }
        $query = function() use($user_id,$start_user): Builder {
            $query = ToDo::where('user_id','=',$user_id)->orderBy('created_at','desc');
            return Eventy::filter(Plugin::FILTER_QUERY_TODO_FOR_USER, $query,$start_user);
        };
        return  $query()->get();
    }

    protected static function booted(): void
    {
        static::creating(function (ToDo $todo) {
            Eventy::action(Plugin::ACTION_MODEL_CREATING.Plugin::ACTION_MODEL_NAME_TODO, $todo);
        });

        static::created(function (ToDo $todo) {
            Eventy::action(Plugin::ACTION_MODEL_CREATED.Plugin::ACTION_MODEL_NAME_TODO, $todo);
        });

        static::updating(function (ToDo $todo) {
            Eventy::action(Plugin::ACTION_MODEL_UPDATING.Plugin::ACTION_MODEL_NAME_TODO, $todo);
        });

        static::updated(function (ToDo $todo) {
            Eventy::action(Plugin::ACTION_MODEL_UPDATED.Plugin::ACTION_MODEL_NAME_TODO, $todo);
        });

        static::deleted(function (ToDo $todo) {
            Eventy::action(Plugin::ACTION_MODEL_DELETED.Plugin::ACTION_MODEL_NAME_TODO, $todo);
        });

        static::deleting(function (ToDo $todo) {
            Eventy::action(Plugin::ACTION_MODEL_DELETING.Plugin::ACTION_MODEL_NAME_TODO, $todo);
        });
    }


}
