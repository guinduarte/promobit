<?php

namespace App\Http\Requests\V1;

use App\Http\Requests\ApiRequest;
use Symfony\Component\Validator\Constraints\Email;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;

class RequestPassword extends ApiRequest
{
    protected function rules(): array
    {
        return [
            'email' => [
                new NotBlank(),
                new Email()
            ],
        ];
    }
}