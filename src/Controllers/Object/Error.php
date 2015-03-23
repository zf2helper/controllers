<?php

namespace Controllers\Object;

class Error
{
    protected $messages = array(
        'missing' => '%s',
        'invalid' => '%s'
    );
    
    public function __construct($type, $paramName, $reason = null)
    {
        $message = sprintf($this->messages[$type], $paramName);
        $this->$type = $message;
        
        if ($reason){
            $this->reason = $reason;
        }
    }
}
