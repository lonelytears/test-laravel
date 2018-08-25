<?php

namespace App\Exceptions;

use Exception;

class QueueException extends Exception
{
    function __construct($msg='')
    {
        parent::__construct($msg);
    }
}
