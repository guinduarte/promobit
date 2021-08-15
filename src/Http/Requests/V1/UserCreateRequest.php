<?php

namespace App\Http\Requests\V1;

use App\Http\Requests\ApiRequest;
use Symfony\Component\Validator\Constraints\Email;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;

class UserCreateRequest extends ApiRequest
{
    protected function rules(): array
    {
        return [
            'name' => [
                new NotBlank(),
            ],
            'email' => [
                new NotBlank(),
                new Email()
            ],
            'password' => [
                new NotBlank(),
            ],
        ];
    }
}