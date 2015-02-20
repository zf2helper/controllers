<?php

namespace Controllers\Model;

class Response extends \Zend\View\Model\JsonModel
{
    
    public function __construct($code, $message, $params)
    {
        parent::__construct();
        
        if (!is_array($params)){
            throw new \Exception('Params must be an array');
        }
        
        $this->setVariable('code', $code);
        $this->setVariable('message', $message);
        $this->setVariable('params', $params);
    }
    
}
