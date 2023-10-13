<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use App\Plugins\Plugin;
use App\Providers\ScmServiceProvider;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Http\UploadedFile;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Intervention\Image\Facades\Image;
use Laravel\Sanctum\HasApiTokens;
use TorMorten\Eventy\Facades\Eventy;

/**
 * @mixin Builder
 * @mixin \Illuminate\Database\Query\Builder
 * @property int id
 * @property int can_log_in
 * @property string username
 * @property string password
 * @property string fname
 * @property string lname
 * @property string email
 * @property string profile
 * @property string title
 * @property int permissions
 * @property string api_key
 * @property string name
 * @property string email_verified_at
 * @property string remember_token
 * @property string created_at
 * @property string updated_at
 *
 * @property ChatMessage[] user_chats
 * @property UserRole[] user_roles
 */
class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'fname',
        'lname',
        'username',
        'profile',
        'title',
        'email',
        'password',
        'id',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];

    const USER_STATUS_SUSPENDED = 0;
    const USER_STATUS_NORMAL = 1;


    public function user_chats() : HasMany {
        return $this->hasMany('App\Models\ChatMessage');
    }

    public function user_todo() : HasMany {
        return $this->hasMany('App\Models\ToDo');
    }

    public function user_audit_logs() : HasMany {
        return $this->hasMany('App\Models\UserLog');
    }

    public function user_project_logs() : HasMany {
        return $this->hasMany('App\Models\DailyLog')
            /** @uses DailyLog::daily_log_files() */
            ->with('daily_log_files');
    }

    /**
     * @return HasMany|UserRole[]
     * @noinspection PhpDocSignatureInspection
     */
    public function user_roles() : HasMany {
        return $this->hasMany('App\Models\UserRole');
    }

    public function has_role(string $role_name) : bool {
        foreach ($this->user_roles()->get() as $role) {
            if ($role->role_name === $role_name) {
                return true;
            }
        }
        return false;
    }


    /**
     * @param string[] $role_names
     * @return void
     * @throws \Exception
     */
    public function save_roles(array $role_names) {
        if (empty($role_names)) {return;} //safety

        $role_names = array_unique($role_names);
        $allowed_roles = array_keys(UserRole::ALL_ROLES);
        $current_roles = [];


        $roles_to_be_deleted = [];
        $roles_to_be_added = [];

        /**
         * @var array<string,UserRole> $role_lookup;
         */
        $role_lookup = [];

        foreach ($this->user_roles()->get() as $role) {
            $current_roles[] = $role->role_name;
            $role_lookup[$role->role_name] = $role;
        }

        foreach ($current_roles as $curr_role) {
            if (!in_array($curr_role,$role_names)) {
                $roles_to_be_deleted[] = $curr_role;
            }
        }


        foreach ($role_names as $name) {
            if (!in_array($name,$allowed_roles)) { continue; }

            if (!in_array($name,$current_roles)) {
                $roles_to_be_added[] = $name;
            }
        }

        //remove the admin from the deleted if the user id here is the logged in user
        if (Auth::user() && Auth::user()->id === $this->id ) {
            if (in_array(UserRole::USER_ROLE_ADMIN,$roles_to_be_deleted)) {
                $roles_to_be_deleted = array_filter($roles_to_be_deleted, fn($e) => $e != UserRole::USER_ROLE_ADMIN);
            }
        }

        if (in_array(UserRole::USER_ROLE_ADMIN,$roles_to_be_added) || in_array(UserRole::USER_ROLE_ADMIN,$current_roles)) {
            $rem_deleted = $roles_to_be_deleted;
            $roles_to_be_deleted = [];
            foreach ($rem_deleted as $rem_del) {
                if (!in_array($rem_del,UserRole::ALL_ADMIN_ROLES)) {
                    $roles_to_be_deleted[] = $rem_del;
                }
            }

            foreach (UserRole::ALL_ADMIN_ROLES as $rem_add) {
                if (!in_array($rem_add,$current_roles) && !in_array($rem_add,$roles_to_be_added)) {
                    $roles_to_be_added[] = $rem_add;
                }
            }
        }

        try {
            DB::beginTransaction();
            foreach ($roles_to_be_deleted as $del_role) {
                $role_lookup[$del_role]?->delete();
            }

            foreach ($roles_to_be_added as $add_role) {
                $working = new UserRole();
                $working->user_id = $this->id;
                $working->role_name = $add_role;
                $working->save();
            }
            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }

    public function getName() : string  {
        return $this->fname . ' ' . $this->lname;
    }

    public function getID() : string  {
        return $this->id;
    }

    public function isAdmin() : bool {
        return $this->has_role(UserRole::USER_ROLE_ADMIN);
    }

    const USER_PERMISSION_ADMIN = 1;
    const USER_PERMISSION_NORMAL = 0;

    const UPLOAD_DIRECTORY = 'uploads/users';
    const UPLOAD_THUMBNAIL_DIRECTORY = 'uploads/users/thumbnails';

    const THUMBNAIL_WIDTH = 200;

    /**
     * The "booted" method of the model.
     */
    protected static function booted(): void
    {
        static::deleted(function (User $user) {
            $user->cleanup_user_resources();
            Eventy::action(Plugin::ACTION_MODEL_DELETED.Plugin::ACTION_MODEL_NAME_USER, $user);
        });

        static::deleting(function (User $user) {

            Eventy::action(Plugin::ACTION_MODEL_DELETING.Plugin::ACTION_MODEL_NAME_USER, $user);

            if (!$user->id) {
                return false;
            }

            /**
             * @var ChatMessage[] $chats
             */
            $chats = $user->user_chats()->get();
            foreach ( $chats as $chat) {
                $chat->delete();
            }

            /**
             * @var DailyLog[] $chats
             */
            $logs = $user->user_project_logs()->get();
            foreach ( $logs as $log) {
                $log->delete();
            }

            /**
             * @var UserLog[] $audit
             */
            $audits = $user->user_audit_logs()->get();
            foreach ( $audits as $audit) {
                $audit->delete();
            }

            /**
             * @var ToDo[] $todos
             */
            $todos = $user->user_todo()->get();
            foreach ( $todos as $todo) {
                $todo->delete();
            }

            /**
             * @var UserRole[] $user_roles
             */
            $user_roles = $user->user_roles()->get();
            foreach ( $user_roles as $user_role) {
                $user_role->delete();
            }



            return true; //allow deletion
        });

        static::creating(function (User $user) {
            Eventy::action(Plugin::ACTION_MODEL_CREATING.Plugin::ACTION_MODEL_NAME_USER, $user);
        });

        static::created(function (User $user) {
            Eventy::action(Plugin::ACTION_MODEL_CREATED.Plugin::ACTION_MODEL_NAME_USER, $user);
        });

        static::updating(function (User $user) {
            Eventy::action(Plugin::ACTION_MODEL_UPDATING.Plugin::ACTION_MODEL_NAME_USER, $user);
        });

        static::updated(function (User $user) {
            Eventy::action(Plugin::ACTION_MODEL_UPDATED.Plugin::ACTION_MODEL_NAME_USER, $user);
        });
    }


    public function cleanup_user_resources() :void {
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
            $what =  ScmServiceProvider::getUserDefaultThumbnail();
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

            $relative_path = $file->storePublicly(User::UPLOAD_DIRECTORY);
            $full_path = storage_path('app/'.$relative_path);
            $thumbnail_relative_path = $file->storePublicly(User::UPLOAD_THUMBNAIL_DIRECTORY);
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

}
