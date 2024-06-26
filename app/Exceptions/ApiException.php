<?php

namespace App\Exceptions;

use Illuminate\Http\JsonResponse;
use Illuminate\Support\Collection;
use Symfony\Component\HttpKernel\Exception\HttpException;

class ApiException extends HttpException
{
    public function __construct(protected $message = null, protected array|Collection $errors = [], protected $code = 500)
    {
        parent::__construct($code, $message);
    }

    public function render(): JsonResponse
    {
        return response()->json([
            'message' => $this->message,
            'errors' => $this->errors,
        ], $this->code);
    }
}
