<?php

namespace App\Http\Requests;

use App\Models\User;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Route;
use Illuminate\Validation\Rule;

class UserUpdateRequest extends FormRequest
{

    protected function getRedirectUrl()
    {
        $called_route = Route::currentRouteName();
        if ($called_route === 'admin.user.create') {
            $this->redirect = 'admin/users/new';
        } elseif ($called_route === 'admin.user.update') {
            $url = $this->redirector->getUrlGenerator();
            return $url->route('admin.user.edit',['user_id'=>$this->route('user_id')]);
        }
        return parent::getRedirectUrl();
    }

    public function otherUser() : ?User
    {
        $some_user_id = $this->route('user_id');
        return User::where('id',$some_user_id)->first();
    }
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\Rule|array|string>
     */
    public function rules(): array
    {
        return [
            'fname' => ['string', 'max:255','required'],
            'lname' => ['string', 'max:255','required'],
            'title' => ['string', 'max:255'],
            'username' => ['string', 'max:255', Rule::unique(User::class)->ignore($this->otherUser()?->id??0)],
            'email' => ['email', 'max:255', Rule::unique(User::class)->ignore($this->otherUser()?->id??0)],
            'profile_picture' => ['file','mimes:jpg,webp,png','max:10240'],
        ];
    }
}
