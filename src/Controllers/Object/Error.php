<?php

namespace Controllers\Object;

class Error
{
    protected $messages = array(
        'missing' => '%s',
        'invalid' => '%s'
    );
    
    public function __construct($type, $paramName)
    {
        $message = sprintf($this->messages[$type], $paramName);
        $this->$type = $message;
    }
}
