<?php

namespace App\Http\Requests;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Symfony\Component\Validator\Constraints\Collection;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class ApiRequest
{
    protected $request, $validator;

    protected $rules = [];

    function __construct(ValidatorInterface $validator)
    {
        $this->validator = $validator;
        $this->request = Request::createFromGlobals();

        $this->validated();
    }

    public function get(string $attribute)
    {
        return isset($this->data()[$attribute]) ? $this->data()[$attribute] : null;
    }

    public function data()
    {
        $data = $this->request->request->all();

        if (0 === strpos($this->request->headers->get('Content-Type'), 'application/json')) {
            $data = json_decode($this->request->getContent(), true);
            $this->request->request->replace(is_array($data) ? $data : array());
        }

        return $data;
    }

    protected function rules(): array
    {
        return [];
    }

    private function validated(): ?bool
    {
        if (count($this->rules())) {
            $validated = $this->validator->validate($this->data(), new Collection($this->rules()));

            $errors = [];

            if (count($validated) > 0) {

                foreach( $validated as $error) {
                    $errors[str_replace(['[',']'], '', $error->getPropertyPath())][] = $error->getMessage();
                }

                throw new HttpException(422, json_encode(['errors' => $errors]));
            }
        }

        return true;
    }
}