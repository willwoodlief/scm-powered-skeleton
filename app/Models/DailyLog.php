<?php

namespace App\Models;

use App\Plugins\Plugin;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use TorMorten\Eventy\Facades\Eventy;

/**
 * @mixin Builder
 * @mixin \Illuminate\Database\Query\Builder
 * @property int id
 * @property int user_id
 * @property int project_id
 * @property string user_type
 * @property string content
 * @property string timestampss
 * @property string created_at
 * @property string updated_at
 *
 * @property DailyLogPhoto[] daily_log_files
 * @property Project parent_project_of_log
 */
class DailyLog extends Model
{
    use HasFactory;

    protected $table = 'daily_logs';

    const USER_TYPE_ADMIN = 'admin';

    /**
     * The "booted" method of the model.
     */
    protected static function booted(): void
    {

        static::creating(function (DailyLog $log) {
            Eventy::action(Plugin::ACTION_MODEL_CREATING.Plugin::ACTION_MODEL_DAILY_LOG, $log);
        });

        static::created(function (DailyLog $log) {
            Eventy::action(Plugin::ACTION_MODEL_CREATED.Plugin::ACTION_MODEL_DAILY_LOG, $log);
        });

        static::updating(function (DailyLog $log) {
            Eventy::action(Plugin::ACTION_MODEL_UPDATING.Plugin::ACTION_MODEL_DAILY_LOG, $log);
        });

        static::updated(function (DailyLog $log) {
            Eventy::action(Plugin::ACTION_MODEL_UPDATED.Plugin::ACTION_MODEL_DAILY_LOG, $log);
        });



        static::deleted(function (DailyLog $log) {
            Eventy::action(Plugin::ACTION_MODEL_DELETED.Plugin::ACTION_MODEL_DAILY_LOG, $log);
        });

        static::deleting(function (DailyLog $log) {

            Eventy::action(Plugin::ACTION_MODEL_DELETING.Plugin::ACTION_MODEL_DAILY_LOG, $log);
            //remove invoices and daily logs here
            /**
             * @var DailyLogPhoto[] $photos
             */
            $photos = $log->daily_log_files()->get();
            foreach ( $photos as $p) {
                $p->delete();
            }

        });
    }


    public function daily_log_files() : HasMany {
        return $this->hasMany('App\Models\DailyLogPhoto')
            /**
             * @uses DailyLogPhoto::parent_log
             */
            ->with('parent_log')
            ;
    }

    public function parent_project_of_log() : BelongsTo {
        return $this->belongsTo('App\Models\Project','project_id','id');
    }


    /**
     * @return Employee|User|null
     */
    public function getWriter() {
        if ($this->user_type === static::USER_TYPE_ADMIN) {
            $query =  User::where('id',$this->user_id);
        } else {
            $query = Employee::where('id',$this->user_id);
        }

        return Eventy::filter(Plugin::FILTER_QUERY_DAILY_LOG_WRITER, $query,$this)->first();
    }



}
