<?php

namespace App\Http\Requests;

use App\Models\User;
use App\Providers\RouteServiceProvider;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Route;
use Illuminate\Validation\Rule;

class ProjectSaveRequest extends FormRequest
{

    protected function getRedirectUrl()
    {
        $called_route = Route::currentRouteName();
        if ($called_route === 'project.create') {
            $this->redirect = 'projects/new';
        } elseif ($called_route === 'project.update') {
            $url = $this->redirector->getUrlGenerator();
            return $url->route('project.edit',['project_id'=>$this->route('project_id')]);
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
            'contractor' => ['integer'],
            'pm' => ['integer'],

            'project_name' => ['string', 'max:255'],
            'address' => ['string', 'max:250'],
            'city' => ['string', 'max:100'],
            'state' => ['string', 'max:2'],
            'zip' => ['string', 'max:5'],
            'latitude' => ['numeric','nullable'],
            'longitude' => ['numeric','nullable'],

            'budget' => ['string', 'max:50'],
            'start_date' => ['date'],
            'end_date' => ['date'],

            'super_name' => ['string', 'max:20'],
            'super_phone' => ['string', 'max:15'],
            'pm_name' => ['string', 'max:20'],
            'pm_phone' => ['string', 'max:15'],

            'status' => ['integer'],


        ];
    }
}
