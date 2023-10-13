<?php

namespace App\Models;

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
 * @property int project_id
 * @property int employee_id
 * @property int status
 * @property string created_at
 * @property string updated_at
 *
 * @property Employee assigned_employee
 */
class ProjectAssign extends Model
{
    use HasFactory;

    protected $table = 'project_assign';

    public function assigned_employee() : BelongsTo {
        return $this->belongsTo('App\Models\Employee','employee_id','id');
    }

    protected static function booted(): void
    {
        static::creating(function (ProjectAssign $assign) {
            Eventy::action(Plugin::ACTION_MODEL_CREATING.Plugin::ACTION_MODEL_PROJECT_ASSIGN, $assign);
        });

        static::created(function (ProjectAssign $assign) {
            Eventy::action(Plugin::ACTION_MODEL_CREATED.Plugin::ACTION_MODEL_PROJECT_ASSIGN, $assign);
        });

        static::updating(function (ProjectAssign $assign) {
            Eventy::action(Plugin::ACTION_MODEL_UPDATING.Plugin::ACTION_MODEL_PROJECT_ASSIGN, $assign);
        });

        static::updated(function (ProjectAssign $assign) {
            Eventy::action(Plugin::ACTION_MODEL_UPDATED.Plugin::ACTION_MODEL_PROJECT_ASSIGN, $assign);
        });

        static::deleted(function (ProjectAssign $assign) {
            Eventy::action(Plugin::ACTION_MODEL_DELETED.Plugin::ACTION_MODEL_PROJECT_ASSIGN, $assign);
        });

        static::deleting(function (ProjectAssign $assign) {
            Eventy::action(Plugin::ACTION_MODEL_DELETING.Plugin::ACTION_MODEL_PROJECT_ASSIGN, $assign);
        });
    }
}
