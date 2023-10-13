<?php

namespace App\Helpers\Employee;

use App\Exceptions\UserException;
use App\Models\Employee;
use App\Models\User;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\Facades\Image;
use Illuminate\Support\Facades\Mail;

class EmployeeToUserSync {

    public static function syncUserIfEmployee(User $user) {
        $employee = Employee::where('user_id',$user->id)->first();
        if ($employee) {
            static::syncEmployeeToUser($employee);
        }
    }
    public static function syncEmployeeToUser(Employee $employee,?string $password = null) {
        /**
         * @var User $user
         */
        $user = $employee->employee_user()->first();
        if ($user) {
            static::updateUser($user,$employee);
        } else {
            if (!$password) {
                throw new \LogicException("Password needs to be set before making web access for employee");
            }
            //make new user
            static::makeNewUser($employee,$password);
        }
    }

    protected static function updateUser(User $user, Employee $employee) {
        if ($employee->email && ( $employee->email !== $user->email ) ) {
            //see if email is used by someone else
            $maybe_other_user = User::whereNot('id',$user->id)->where('email',$employee->email)->first();
            if (!$maybe_other_user) {
                $user->email = $employee->email;
            }
        }

        if ($employee->first_name && $user->fname !== $employee->first_name) {
            $user->fname = $employee->first_name;
        }

        if ($employee->last_name && $user->lname !== $employee->last_name) {
            $user->lname = $employee->last_name;
        }


        $user->save();

        if (!$user->profile && $employee->profile) {
            static::copyProfileImage($user,$employee);
        }
    }

    protected static function makeNewUser(Employee $employee,string $password) {
        $check_user = $employee->employee_user()->first();
        if ($check_user) {
            $file = __FILE__;
            $line = __LINE__;
            Log::error("$file: $line Was told to make new user but it already exists, employee data follows",$employee->toArray());
            throw new \RuntimeException("Cannot make user because it already exists for employee");
        }

        if (!$employee->email) {
            throw new UserException("Cannot make web access for employee because employee's email is not set yet");
        }
        $maybe_other_user = User::where('email',$employee->email)->first();
        if ($maybe_other_user) {
            throw new UserException("Cannot make web access for employee because the email is already in use. Please change email: $employee->email");
        }

        $some_user = new User();
        $some_user->email = $employee->email;
        $some_user->lname = $employee->last_name;
        $some_user->fname = $employee->first_name;
        $some_user->email_verified_at = null;
        $some_user->permissions = User::USER_PERMISSION_NORMAL;
        $some_user->api_key = '';
        $some_user->name = $some_user->getName();
        $some_user->password = password_hash($password, PASSWORD_DEFAULT);
        $some_user->profile = '';
        $possible_user_name = $some_user->name;
        $possible_user_name = mb_strtolower(str_replace('-',' ',$possible_user_name));
        $possible_user_name = mb_strtolower(str_replace('_',' ',$possible_user_name));

        $possible_user_name  = preg_replace( "/[[:punct:]]/", "", $possible_user_name);

        $possible_user_name = mb_strtolower(str_replace(' ','_',$possible_user_name));
        $possible_user_name  = preg_replace(/** @lang text */ "/\s+/", "", $possible_user_name);

        $some_user->username = substr($possible_user_name,0,50 );
        $some_user->save();

        $employee->user_id = $some_user->id;
        $employee->save();
        static::copyProfileImage($some_user,$employee);

        Mail::to($some_user)->send(new \App\Mail\Welcome($some_user,$employee,$password));
    }

    protected static function copyProfileImage(User $user, Employee $employee) {
        if (!$employee->profile) {
            return;
        }
        //only copy over if the file is set and existing
        $employee_full_sized_image_abs_path = $employee->get_image_full_path();
        if (!is_readable($employee_full_sized_image_abs_path)) {
            return;
        }

        $employee_full_sized_relative_path = $employee->get_image_relative_path();
        $user_full_sized_relative_path = User::UPLOAD_DIRECTORY . DIRECTORY_SEPARATOR .  $employee->profile;
        Storage::copy($employee_full_sized_relative_path, $user_full_sized_relative_path);
        if (file_exists($user->get_image_full_path())) {
            unlink($user->get_image_full_path());
        }

        //only copy over if the file is set and existing
        $employee_thumb_sized_image_abs_path = $employee->get_image_full_path(true);
        if (is_readable($employee_thumb_sized_image_abs_path)) {
            $employee_thumb_relative_path = $employee->get_image_relative_path(true);
            $user_thumb_sized_relative_path = User::UPLOAD_THUMBNAIL_DIRECTORY . DIRECTORY_SEPARATOR .  $employee->profile;
            Storage::copy($employee_thumb_relative_path, $user_thumb_sized_relative_path);

            if (file_exists($user->get_image_full_path(true))) {
                unlink($user->get_image_full_path(true));
            }
        } else {
            $full_image_abs_path = storage_path('app/'.User::UPLOAD_DIRECTORY . DIRECTORY_SEPARATOR .  $employee->profile);
            $thumb_image_abs_path = storage_path('app/'.User::UPLOAD_THUMBNAIL_DIRECTORY . DIRECTORY_SEPARATOR .  $employee->profile);

            Image::make($full_image_abs_path)->resize(User::THUMBNAIL_WIDTH, null,function ($constraint) {
                $constraint->aspectRatio();
                $constraint->upsize();
            })->save($thumb_image_abs_path);
        }

        $user->profile = $employee->profile;
        $user->save();

    }
}
