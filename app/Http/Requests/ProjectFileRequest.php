<?php

namespace App\Http\Requests;

use App\Models\User;
use App\Providers\RouteServiceProvider;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class ProjectFileRequest extends FormRequest
{

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
            'file' => ['file','required','mimes:jpg,webp,png,pdf,txt,doc,xcl','max:10240']
        ];
    }
}
