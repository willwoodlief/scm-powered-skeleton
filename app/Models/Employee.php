<?php

namespace App\Models;

use App\Plugins\Plugin;
use App\Providers\ScmServiceProvider;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Http\UploadedFile;
use Intervention\Image\Facades\Image;
use TorMorten\Eventy\Facades\Eventy;

/**
 * @mixin Builder
 * @mixin \Illuminate\Database\Query\Builder
 * @property int id
 * @property int user_id
 * @property string first_name
 * @property string last_name
 * @property string dob
 * @property string address
 * @property float latitude
 * @property float longitude
 * @property string city
 * @property string state
 * @property int zip
 * @property string phone
 * @property string email
 * @property string password
 * @property string hire_date
 * @property int role
 * @property string profile
 * @property string profile_bg
 * @property int status
 * @property string token
 * @property string created_at
 * @property string updated_at
 *
 * @property User employee_user
 */
class Employee extends Model
{
    use HasFactory;

    protected $table = 'employees';

    const UPLOAD_DIRECTORY = 'uploads/employees';
    const UPLOAD_THUMBNAIL_DIRECTORY = 'uploads/employees/thumbnails';

    const THUMBNAIL_WIDTH = 200;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'first_name',
        'last_name',
        'dob',
        'address',
        'latitude',
        'longitude',
        'city',
        'state',
        'zip',
        'phone',
        'email',
        'hire_date',
        'role',
        'profile',
        'profile_bg',
        'latitude',
        'longitude',
    ];


    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'token',
    ];

    public static function getBackgroundImages() {
        return [
            __('Fall Leaves')=> 'profile_bg.jpeg',
            __('Island')=> 'profile_bg2.jpeg',
            __('Snow Mountains')=> 'profile_bg3.jpeg',
            __('Bridge')=> 'profile_bg4.jpeg',
            __('Bricks')=> 'profile_bg5.jpeg'
        ];
    }

    public function employee_user() : BelongsTo {
        return $this->belongsTo('App\Models\User','user_id','id');
    }

    /**
     * The "booted" method of the model.
     */
    protected static function booted(): void
    {
        static::saving(function(Employee $employee) {
            if (empty($employee->latitude)) {$employee->latitude = 0;}
            if (empty($employee->longitude)) {$employee->longitude = 0;}
        });

        static::deleted(function (Employee $employee) {
            $employee->cleanup_employee_resources();
            Eventy::action(Plugin::ACTION_MODEL_DELETED.Plugin::ACTION_MODEL_EMPLOYEE, $employee);
        });

        static::deleting(function (Employee $employee) {

            if (!$employee->id) {
                return false;
            }

            Eventy::action(Plugin::ACTION_MODEL_DELETING.Plugin::ACTION_MODEL_EMPLOYEE, $employee);

            /**
             * @var DeviceDetail[] $devices
             */
            $devices = $employee->employee_devices()->get();
            foreach ( $devices as $device) {
                $device->delete();
            }


            /**
             * @var ProjectAssign[] $project_assigns
             */
            $project_assigns = $employee->employee_assigns()->get();
            foreach ( $project_assigns as $an_assign) {
                $an_assign->delete();
            }


            return true; //allow deletion
        });

        static::creating(function (Employee $employee) {
            Eventy::action(Plugin::ACTION_MODEL_CREATING.Plugin::ACTION_MODEL_EMPLOYEE, $employee);
        });

        static::created(function (Employee $employee) {
            Eventy::action(Plugin::ACTION_MODEL_CREATED.Plugin::ACTION_MODEL_EMPLOYEE, $employee);
        });

        static::updating(function (Employee $employee) {
            Eventy::action(Plugin::ACTION_MODEL_UPDATING.Plugin::ACTION_MODEL_EMPLOYEE, $employee);
        });

        static::updated(function (Employee $employee) {
            Eventy::action(Plugin::ACTION_MODEL_UPDATED.Plugin::ACTION_MODEL_EMPLOYEE, $employee);
        });

    }

    public function employee_devices() : HasMany {
        return $this->hasMany('App\Models\DeviceDetail');
    }

    public function employee_assigns() : HasMany {
        return $this->hasMany('App\Models\ProjectAssign');
    }

    const STATUS_ACTIVE = 1;
    const DEFAULT_STATUS = 1;
    const DEFAULT_PW = '';

    const ROLE_PRESIDENT = 1;
    const ROLE_PROJECT_MANAGER = 2;
    const ROLE_FOREMAN = 3;
    const ROLE_CREW_LEAD = 4;
    const ROLE_LABORER = 5;
    const ROLE_OFFICE = 6;


    function humanRole() :string {
        $id = $this->role;
        if ($id == static::ROLE_PRESIDENT) {
            return "President";
        }else if ($id == static::ROLE_PROJECT_MANAGER) {
            return "Project Manager";
        }else if ($id == static::ROLE_FOREMAN) {
            return "Foreman";
        }else if ($id == static::ROLE_CREW_LEAD) {
            return "Crew Lead";
        }else if ($id == static::ROLE_LABORER) {
            return "Laborer";
        }else if ($id == static::ROLE_OFFICE) {
            return "Office";
        } else {
            return "Not set";
        }
    }

    function humanStatus() :string {
        if ($this->status === static::STATUS_ACTIVE) {
            return "Active";
        }else {
            return "Terminated";
        }
    }

    public function cleanup_employee_resources() :void {
        if (!$this->profile) { return ;}

        if (file_exists($this->get_image_full_path())) {
            unlink($this->get_image_full_path());
        }
        if (file_exists($this->get_image_full_path(true))) {
            unlink($this->get_image_full_path(true));
        }
    }

    public function get_image_asset_path(bool $thumbnail = false) {
        if (!$this->image_exists($thumbnail)) {
            $what =  ScmServiceProvider::getEmployeeDefaultThumbnail();
        }
        else {
            $what = $this->get_image_relative_path($thumbnail);
        }
        return asset($what);
    }
    public function get_image_relative_path(bool $thumbnail = false) : ?string {
        if (!$this->profile) {
            return null;
        }
        $partial = static::UPLOAD_DIRECTORY;
        if ($thumbnail) {
            $partial = static::UPLOAD_THUMBNAIL_DIRECTORY;
        }
        return $partial.'/'.$this->profile;
    }

    public function get_image_full_path(bool $thumbnail = false) : ?string  {
        $partial = $this->get_image_relative_path($thumbnail);
        if (!$partial) {return null;}
        return storage_path('app/'.$partial);
    }

    public function image_exists(bool $thumbnail = false) : bool {
        $path = $this->get_image_full_path($thumbnail);
        if (!$path) {return false;}
        return file_exists($path);
    }

    /**
     * @param UploadedFile $file
     * @return void
     * @throws
     */
    public function process_uploaded_image(UploadedFile $file ) : void {
        $full_path = null;
        $thumbnail_full_path = null;
        try {

            $relative_path = $file->storePublicly(Employee::UPLOAD_DIRECTORY);
            $full_path = storage_path('app/'.$relative_path);
            $thumbnail_relative_path = $file->storePublicly(Employee::UPLOAD_THUMBNAIL_DIRECTORY);
            $thumbnail_full_path = storage_path('app/'.$thumbnail_relative_path);
            $image_name = $file->hashName();
            Image::make($thumbnail_full_path)->resize(static::THUMBNAIL_WIDTH, null,function ($constraint) {
                $constraint->aspectRatio();
                $constraint->upsize();
            })->save();

            if ($this->profile) {
                if (file_exists($this->get_image_full_path())) {
                    unlink($this->get_image_full_path());
                }
                if (file_exists($this->get_image_full_path(true))) {
                    unlink($this->get_image_full_path(true));
                }

            }
            $this->profile = $image_name;



        } catch (\Exception $what) {
            if($thumbnail_full_path) {unlink($thumbnail_full_path);}
            if($full_path) {unlink($full_path);}
            throw $what;
        }
    }

    public function getName() : string  {
        return $this->first_name . ' ' . $this->last_name;
    }

    /**
     * @return Employee[]|\Illuminate\Database\Eloquent\Collection|\Illuminate\Support\Collection
     */
    public static function getBirthdaysThisMonth() {

        $query = function() : Builder {
            $currentMonth = date('m');
            $query = Employee::whereRaw("MONTH(dob) = $currentMonth")->orderBy('id');
            return Eventy::filter(Plugin::FILTER_QUERY_EMPLOYEE_BIRTHDAYS, $query);
        };

        return $query()->get();
    }

    /**
     * @return Employee[]
     */
    public static function getAllEmployees() {
        $query = function() : Builder {
            $query = Employee::where('id','>',0);
            return Eventy::filter(Plugin::FILTER_QUERY_EMPLOYEES, $query);
        };

        return $query()->get();
    }


    /**
     * @return Employee[]
     */
    public static function getAllForemen() {
        $query = function() : Builder {
            $query = Employee::where('role',Employee::ROLE_FOREMAN);
            return Eventy::filter(Plugin::FILTER_QUERY_FOREMEN, $query);
        };

        return $query()->get();
    }

    /**
     * @return Employee[]
     */
    public static function getAllProjectManagers() {
        $query = function() : Builder {
            $query = Employee::where('role',Employee::ROLE_PROJECT_MANAGER);
            return Eventy::filter(Plugin::FILTER_QUERY_PROJECT_MANAGERS, $query);
        };

        return $query()->get();
    }
}
