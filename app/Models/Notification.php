<?php

namespace App\Models;

use App\Plugins\Plugin;
use GuzzleHttp\Exception\GuzzleException;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use TorMorten\Eventy\Facades\Eventy;

/**
 * @mixin Builder
 * @mixin \Illuminate\Database\Query\Builder
 * @property int id
 * @property string employee_id
 * @property int timestamp
 * @property string title
 * @property string description
 * @property string server_response_json
 *
 */
class Notification extends Model
{
    use HasFactory;

    protected $table = 'notifications';
    public $timestamps = false;

    const NOTIFICATION_TYPE_SELECT_EMPLOYEE = 'selectemployee';
    const NOTIFICATION_TYPE_ALL_EMPLOYEES = 'allemployees';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'title',
        'description'
    ];


    protected static function booted(): void
    {
        static::creating(function (Notification $notification) {
            Eventy::action(Plugin::ACTION_MODEL_CREATING.Plugin::ACTION_MODEL_NOTIFICATION, $notification);
        });

        static::created(function (Notification $notification) {
            Eventy::action(Plugin::ACTION_MODEL_CREATED.Plugin::ACTION_MODEL_NOTIFICATION, $notification);
        });

        static::updating(function (Notification $notification) {
            Eventy::action(Plugin::ACTION_MODEL_UPDATING.Plugin::ACTION_MODEL_NOTIFICATION, $notification);
        });

        static::updated(function (Notification $notification) {
            Eventy::action(Plugin::ACTION_MODEL_UPDATED.Plugin::ACTION_MODEL_NOTIFICATION, $notification);
        });

        static::deleted(function (Notification $notification) {
            Eventy::action(Plugin::ACTION_MODEL_DELETED.Plugin::ACTION_MODEL_NOTIFICATION, $notification);
        });

        static::deleting(function (Notification $notification) {
            Eventy::action(Plugin::ACTION_MODEL_DELETING.Plugin::ACTION_MODEL_NOTIFICATION, $notification);
        });
    }


    /**
     * @param array $emid
     * @param string $title
     * @param string $desc
     * @param string $type
     * @return Notification
     * @throws GuzzleException
     */
    public static function createNotification(array $emid, string $title, string $desc, string $type)
    : Notification
    {

        $server_response = static::send_notification($emid,$title,$desc,$type);


        $comma_ids = implode(',',$emid);
        $safe_comma_ids = substr($comma_ids, 0, 255);

        $noti = new Notification();
        $noti->employee_id = $safe_comma_ids;
        $noti->timestamp = time();
        $noti->description = $desc;
        $noti->title = $title;
        $noti->server_response_json = json_encode($server_response);
        $noti->save();
        return $noti;
    }

    /**
     * @throws GuzzleException
     * @return array
     */
    protected static function send_notification(array $emid, string $title, string $desc, string $type)
    {

        if($type=== static::NOTIFICATION_TYPE_ALL_EMPLOYEES) {
            $device_tokens = DeviceDetail::where('id','>',0)->pluck('device_token');
        }else {
            $device_tokens = DeviceDetail::whereIn('employee_id',$emid)->pluck('device_token');
        }

        $API_SERVER_KEY= config('scm.firebase_cloud_messaging_key');


        $msg = array
        (
            'body'  => $desc,
            'title' => $title,
            'icon'  => 'myicon',
            'sound' => 'mySound'
        );
        $fields = array
        (
            'registration_ids' => $device_tokens,
            'notification'  => $msg
        );
        $headers = array
        (
            'Authorization: key=' . $API_SERVER_KEY,
            'Content-Type: application/json'
        );

        if(!config('app.allow_notifications')) {
            return [
                'debug_state' => "Not sending because ALLOW_NOTIFICATIONS is set to false in the env",
                'headers' => $headers,
                "body" => $fields,
            ];
        }
        $client = new \GuzzleHttp\Client();

        $request = $client->post("https://fcm.googleapis.com/fcm/send",[
            'headers' => $headers,
            "body" => $fields,
        ]);
        return json_decode($request->getBody(), true);
    }

    /**
     * @return Invoice[]
     */
    public static function getAllNotifications() {
        $query = function() : Builder {
            $query = Notification::where('id','>',0)->orderBy('id','desc');
            return Eventy::filter(Plugin::FILTER_QUERY_NOTIFICATIONS, $query);
        };

        return $query()->get();
    }

}
