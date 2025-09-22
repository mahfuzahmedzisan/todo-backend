<?php

namespace App\Http\Requests\API;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Http\Exceptions\HttpResponseException;


class BaseApiRequest extends FormRequest
{
    protected function failedValidation(Validator $validator): void
    {
        $errors = $validator->errors();
        $response = response()->json([
            'success' => false,
            'data' => $errors->messages(),
        ], Response::HTTP_UNPROCESSABLE_ENTITY);
        throw new HttpResponseException($response);
    }
}
