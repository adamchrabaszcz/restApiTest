<?php

namespace AppBundle\Exception;

use Symfony\Component\HttpKernel\Exception\HttpException;

class MethodNotAllowedException extends HttpException
{

    public function __construct($message, $code = 0, \Exception $previous = null, $httpCode = 500)
    {
        parent::__construct($httpCode, $message, $previous, array(), $code);
    }
}
