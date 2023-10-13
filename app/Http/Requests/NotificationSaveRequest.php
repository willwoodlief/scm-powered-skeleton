<?php

namespace App\Http\Requests;

use App\Models\Notification;
use App\Models\User;
use App\Providers\RouteServiceProvider;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Route;
use Illuminate\Validation\Rule;

class NotificationSaveRequest extends FormRequest
{

    protected function getRedirectUrl()
    {
        $called_route = Route::currentRouteName();
        if ($called_route === 'notifications.create') {
            $this->redirect = 'notifications/new';
        }
        return parent::getRedirectUrl();
    }
    /**
     * Get data to be validated from the request.
     *
     * @return array
     */
    public function validationData()
    {
        return parent::validationData();
    }

    const FORM_NAME_NOTIFICATION_TYPE = 'employees';
    const FORM_NAME_EMPLOYEE_ID_LIST = 'employee_id_selected';
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\Rule|array|string>
     */
    public function rules(): array
    {
        $employee_types = [
            Notification::NOTIFICATION_TYPE_ALL_EMPLOYEES,Notification::NOTIFICATION_TYPE_SELECT_EMPLOYEE
        ];

        return [
            'title' => ['string', 'required','max:100','min:1'],
            'description' => ['string', 'required','min:1'],
            static::FORM_NAME_NOTIFICATION_TYPE => ['string', 'in:'.implode(',',$employee_types)],
            static::FORM_NAME_EMPLOYEE_ID_LIST    => "array",
            static::FORM_NAME_EMPLOYEE_ID_LIST.".*"  => "numeric|distinct"
        ];
    }
}
