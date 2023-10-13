<?php

namespace App\Models;

use App\Plugins\Plugin;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use TorMorten\Eventy\Facades\Eventy;

/**
 * @property int id
 * @property string company_name
 * @property string username
 * @property string password
 * @property string database_name
 * @property string logo
 * @property string created_at
 * @property string updated_at
 */
class Company extends Model
{
    use HasFactory;

    protected $table = 'company';

    protected static function booted(): void
    {
        static::creating(function (Company $company) {
            Eventy::action(Plugin::ACTION_MODEL_CREATING.Plugin::ACTION_MODEL_COMPANY, $company);
        });

        static::created(function (Company $company) {
            Eventy::action(Plugin::ACTION_MODEL_CREATED.Plugin::ACTION_MODEL_COMPANY, $company);
        });

        static::updating(function (Company $company) {
            Eventy::action(Plugin::ACTION_MODEL_UPDATING.Plugin::ACTION_MODEL_COMPANY, $company);
        });

        static::updated(function (Company $company) {
            Eventy::action(Plugin::ACTION_MODEL_UPDATED.Plugin::ACTION_MODEL_COMPANY, $company);
        });

        static::deleted(function (Company $company) {
            Eventy::action(Plugin::ACTION_MODEL_DELETED.Plugin::ACTION_MODEL_COMPANY, $company);
        });

        static::deleting(function (Company $company) {
            Eventy::action(Plugin::ACTION_MODEL_DELETING.Plugin::ACTION_MODEL_COMPANY, $company);
        });
    }
}
