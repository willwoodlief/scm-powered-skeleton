<?php

namespace App\Http\Requests;

use App\Models\User;
use App\Providers\RouteServiceProvider;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Route;
use Illuminate\Validation\Rule;

class EmployeeSaveRequest extends FormRequest
{

    protected function getRedirectUrl()
    {
        $called_route = Route::currentRouteName();
        if ($called_route === 'employee.create') {
            $this->redirect = 'employees/new';
        } elseif ($called_route === 'employee.update') {
            $url = $this->redirector->getUrlGenerator();
            return $url->route('employee.edit',['employee_id'=>$this->route('employee_id')]);
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
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\Rule|array|string>
     */
    public function rules(): array
    {
        return [
            'first_name' => ['string', 'max:20'],
            'last_name' => ['string', 'max:20'],
            'address' => ['string', 'max:50'],
            'city' => ['string', 'max:20'],
            'state' => ['string', 'max:2'],
            'zip' => ['string', 'max:7'],
            'phone' => ['string', 'max:11'],
            'email' => ['string','email', 'max:40'],
            'role' => ['integer', 'min:1','max:10'],
            'dob' => ['date'],
            'hire_date' => ['date'],
            'profile_bg' => ['string', 'max:20'],
            'latitude' => ['numeric','nullable'],
            'longitude' => ['numeric','nullable'],
        ];
    }
}
