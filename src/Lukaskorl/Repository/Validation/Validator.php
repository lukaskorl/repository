<?php namespace Lukaskorl\Repository\Validation;

use Illuminate\Validation\Factory;

abstract class Validator {
    /**
     * @var \Illuminate\Validation\Factory
     */
    protected $validator;

    /**
     * @param Factory $validator
     */
    function __construct(Factory $validator)
    {
        $this->validator = $validator;
    }

    public function handle($data)
    {
        $validation = $this->validator->make($data, static::$rules);

        if ($validation->fails()) throw new ValidationException($validation->messages());

        return true;
    }
}