<?php

namespace App\Http\Resources\V1;

use App\Http\Resources\ApiResource;

class UserResource extends ApiResource
{
    protected function contract(): array
    {
        return [
            'id',
            'name',
            'email',
        ];
    }
}