<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Route;


class ContractorSaveRequest extends FormRequest
{
    protected function getRedirectUrl()
    {
        $called_route = Route::currentRouteName();
        if ($called_route === 'contractor.create') {
            $this->redirect = 'contractors/new';
        } elseif ($called_route === 'contractor.edit') {
            $url = $this->redirector->getUrlGenerator();
            return $url->route('contractor.edit',['contractor_id'=>$this->route('contractor_id')]);
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
            'name' => ['string', 'max:50'],
            'address' => ['string', 'max:50'],
            'city' => ['string', 'max:25'],
            'state' => ['string', 'max:2'],
            'zip' => ['string', 'max:7'],
            'phone' => ['string', 'max:11']
        ];
    }
}
