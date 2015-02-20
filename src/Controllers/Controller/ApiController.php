<?php

namespace Controllers\Controller;

use Zend\View\Model\JsonModel;

abstract class ApiController extends \Zend\Mvc\Controller\AbstractActionController
{
    /**
     * Get entity manager
     * 
     * @return \Doctrine\ORM\EntityManager
     */
    public function getEntityManager()
    {
        return $this->getServiceLocator()->get('doctrine.entitymanager.orm_default');
    }
    
    /**
     * Auth user
     * 
     * @return boolean
     */
    public function auth($username, $password)
    {
        $authService = $this->getServiceLocator()->get('Zend\Authentication\AuthenticationService');
        $adapter = $authService->getAdapter();
        $adapter->setIdentityValue($username);
        $adapter->setCredentialValue(md5($password));
        $authResult = $authService->authenticate();

        if ($authResult->isValid()) {
            $user = $authResult->getIdentity();
            $authService->getStorage()->write($user);
        }

        return ($authResult->isValid() == true ? $user : false );        
    }
    
    /**
     * Check Api Auth
     * @return boolen
     */
    public function checkToken($token, $entity)
    {
        if (!$token){
            return false;
        }
        
        $user = $this->getEntityManager()
                ->getRepository($entity)
                ->findOneByToken($token);
        
        if (!$user){
            return false;
        }
        
        return $user;
    }
    
    /**
     * Get base host uri from request
     * @return string Base url
     */
    public function getBaseUrl()
    {
        $uri = $this->getRequest()->getUri();
        $baseUrl = sprintf('%s://%s', $uri->getScheme(), $uri->getHost());
        return $baseUrl;
    }
    
    public function apiResponse($statusCode, $responseCode, $message, $params)
    {
        $this->getResponse()->setStatusCode($statusCode);
        $response = new \V1\Model\Response($responseCode, $message, $params);
        
        return $response;
    }
    
}
