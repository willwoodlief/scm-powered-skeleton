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
 * @property int user_id
 * @property string role_name
 * @property string created_at
 * @property string updated_at
 *
 * @property User role_user
 */
class UserRole extends Model
{
    use HasFactory;

    protected $table = 'user_role';

    protected $fillable = [
        'user_id',
        'role_name',
    ];

    /*
     Admin, HR, Project Manager, foreman, and employee, accounting
     */
    const USER_ROLE_ADMIN = 'admin';
    const USER_ROLE_HR = 'hr';
    const USER_ROLE_ACCOUNTING = 'accounting';
    const USER_ROLE_PROJECT_MANAGER = 'project_manager';
    const USER_ROLE_FOREMAN = 'foreman';
    const USER_ROLE_EMPLOYEE = 'employee';

    const ALL_ROLES = [
      self::USER_ROLE_ADMIN => 'Full Administrator',
      self::USER_ROLE_HR => 'Human Resources',
      self::USER_ROLE_ACCOUNTING => 'Accounting',
      self::USER_ROLE_PROJECT_MANAGER => 'Project Manager',
      self::USER_ROLE_FOREMAN => 'Foreman',
      self::USER_ROLE_EMPLOYEE => 'Employee',
    ];

    const ALL_ADMIN_ROLES = [
        self::USER_ROLE_HR,
        self::USER_ROLE_ACCOUNTING,
        self::USER_ROLE_PROJECT_MANAGER,
        self::USER_ROLE_FOREMAN
    ];

    public function role_user() : BelongsTo {
        return $this->belongsTo('App\Models\User','user_id','id');
    }


    protected static function booted(): void
    {
        static::creating(function (UserRole $todo) {
            Eventy::action(Plugin::ACTION_MODEL_CREATING.Plugin::ACTION_MODEL_NAME_USER_ROLE, $todo);
        });

        static::created(function (UserRole $todo) {
            Eventy::action(Plugin::ACTION_MODEL_CREATED.Plugin::ACTION_MODEL_NAME_USER_ROLE, $todo);
        });

        static::updating(function (UserRole $todo) {
            Eventy::action(Plugin::ACTION_MODEL_UPDATING.Plugin::ACTION_MODEL_NAME_USER_ROLE, $todo);
        });

        static::updated(function (UserRole $todo) {
            Eventy::action(Plugin::ACTION_MODEL_UPDATED.Plugin::ACTION_MODEL_NAME_USER_ROLE, $todo);
        });

        static::deleted(function (UserRole $todo) {
            Eventy::action(Plugin::ACTION_MODEL_DELETED.Plugin::ACTION_MODEL_NAME_USER_ROLE, $todo);
        });

        static::deleting(function (UserRole $todo) {
            Eventy::action(Plugin::ACTION_MODEL_DELETING.Plugin::ACTION_MODEL_NAME_USER_ROLE, $todo);
        });
    }


}
