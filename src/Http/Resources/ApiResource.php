<?php

namespace App\Http\Resources;

use Symfony\Component\Serializer\Normalizer\AbstractNormalizer;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Serializer;

class ApiResource
{
    protected $data;

    function __construct($data = [])
    {
        $this->data = $data;

        $this->serialize();
    }

    public function getData()
    {
        return $this->data;
    }

    protected function contract(): array
    {
        return [];
    }

    public function serialize()
    {
        $serializer = new Serializer([new ObjectNormalizer()]);

        $this->data = $serializer->normalize($this->data, null, [AbstractNormalizer::ATTRIBUTES => $this->contract()]);
    }
}