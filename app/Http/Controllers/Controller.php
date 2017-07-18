<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Validation\ValidationException;


class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    protected $rules = [];

    public function ajaxValidate(array $data, array $rules = [], array $messages = [], array $customAttributes = [])
    {
        $this->rules = array_merge($this->rules, $rules);
        $validator = $this->getValidationFactory()->make($data, $this->rules, $messages, $customAttributes);
        if ($validator->fails()) {
            $errorData = [
                'data' => [],
                'fieldErrors' => [],
            ];
            foreach ($validator->errors()->messages() as $key => $message) {
                $errorData['fieldErrors'][] = [
                    'name' => $key,
                    'status' => $message[0]
                ];
            }
            throw new ValidationException($validator, new JsonResponse($errorData));
        }
    }

    /**
     * {@inheritdoc}
     */
//    protected function formatValidationErrors(Validator $validator)
//    {
//        return $validator->errors()->all();
//    }
}
