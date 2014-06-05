<?php namespace Lukaskorl\Repository\Validation;

use Exception;

class ValidationException extends Exception {
    /**
     * @var mixed
     */
    private $errors;

    /**
     * @param mixed $errors
     */
    function __construct($errors)
    {
        $this->errors = $errors;
    }

    /**
     * @return mixed
     */
    public function getErrors()
    {
        return $this->errors;
    }
}