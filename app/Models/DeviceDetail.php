<?php

namespace App\Models;

use App\Plugins\Plugin;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use TorMorten\Eventy\Facades\Eventy;

/**
 * @mixin Builder
 * @mixin \Illuminate\Database\Query\Builder
 * @property int id
 * @property int employee_id
 * @property string device_type
 * @property string device_token
 * @property string thumbnail_name
 * @property string created_at
 * @property string updated_at
 */
class DeviceDetail extends Model
{
    use HasFactory;

    protected $table = 'device_detail';

    protected static function booted(): void
    {
        static::creating(function (DeviceDetail $device_detail) {
            Eventy::action(Plugin::ACTION_MODEL_CREATING.Plugin::ACTION_MODEL_DEVICE_DETAIL, $device_detail);
        });

        static::created(function (DeviceDetail $device_detail) {
            Eventy::action(Plugin::ACTION_MODEL_CREATED.Plugin::ACTION_MODEL_DEVICE_DETAIL, $device_detail);
        });

        static::updating(function (DeviceDetail $device_detail) {
            Eventy::action(Plugin::ACTION_MODEL_UPDATING.Plugin::ACTION_MODEL_DEVICE_DETAIL, $device_detail);
        });

        static::updated(function (DeviceDetail $device_detail) {
            Eventy::action(Plugin::ACTION_MODEL_UPDATED.Plugin::ACTION_MODEL_DEVICE_DETAIL, $device_detail);
        });

        static::deleted(function (DeviceDetail $device_detail) {
            Eventy::action(Plugin::ACTION_MODEL_DELETED.Plugin::ACTION_MODEL_DEVICE_DETAIL, $device_detail);
        });

        static::deleting(function (DeviceDetail $device_detail) {
            Eventy::action(Plugin::ACTION_MODEL_DELETING.Plugin::ACTION_MODEL_DEVICE_DETAIL, $device_detail);
        });
    }
}
