<?php

namespace App\Http\Requests;

use App\Models\User;
use App\Providers\RouteServiceProvider;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Route;
use Illuminate\Validation\Rule;

class ProjectExpenseSaveRequest extends FormRequest
{

    /**
     * Get the URL to redirect to on a validation error.
     *
     * @return string
     */
    protected function getRedirectUrl()
    {
        $called_route = Route::currentRouteName();
        if ($called_route === 'transactions.create') {
            $this->redirect = 'transactions/new';
        } elseif ($called_route === 'transactions.update') {
            $url = $this->redirector->getUrlGenerator();
            return $url->route('transactions.edit',['expense_id'=>$this->route('expense_id')]);
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
            'project_id' => ['integer','required'],
            'transaction_type' => ['string', 'required', 'max:255'],
            'date' => ['date','required'],
            'business' => ['string', 'required', 'max:255'],
            'description' => ['string', 'required', 'max:255'],
            'employee_id' => ['string', 'required', 'max:50'],
            'amount' => ['numeric', 'required'],
        ];
    }
}
