<?php

namespace App\Exceptions;

use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Log;

class UserException extends \RuntimeException {

    private string $title;
    public function __construct(
        string $title,
        #[LanguageLevelTypeAware(['8.0' => 'string'], default: '')] $message = "",
        #[LanguageLevelTypeAware(['8.0' => 'int'], default: '')] $code = 409,
        #[LanguageLevelTypeAware(['8.0' => 'Throwable|null'], default: 'Throwable')] $previous = null
    )
    {
        parent::__construct($message,$code,$previous);
        $this->title = $title;
    }

    public function getTitle(): string
    {
        return $this->title;
    }

    /**
     * Render the exception into an HTTP response.
     */
    public function render(Request $request): Response
    {
        return response(view('errors.user-http', ['request' => $request])->with('issue',$this)->render(),$this->getCode());
    }

    public function report(): void
    {
        Log::info($this->getTitle() . ': '. $this->getMessage() . ', '. $this->getFile() . ': '. $this->getLine());
    }
}
