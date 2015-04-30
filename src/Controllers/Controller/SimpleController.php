<?php

namespace Controllers\Controller;

abstract class SimpleController extends \Zend\Mvc\Controller\AbstractActionController
{
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
    
    /**
     * Get entity manager
     * 
     * @return \Doctrine\ORM\EntityManager
     */
    public function getEntityManager()
    {
        return $this->getServiceLocator()->get('doctrine.entitymanager.orm_default');
    }
    
    public function getViewHelper($helperName)
    {
        return $this->getServiceLocator()->get('viewhelpermanager')->get($helperName);
    }
    
    public function getBasePath()
    {
        $this->getViewHelper('BasePath');
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
     * Return current auth user or false
     * 
     * @return \Core\Entity\User
     */
    public function getAuth()
    {
        $authService = $this->getServiceLocator()->get('Zend\Authentication\AuthenticationService');
        $loggedUser = $authService->getIdentity();

        return $loggedUser;
    }

    public function logout()
    {
        $authService = $this->getServiceLocator()->get('Zend\Authentication\AuthenticationService');
        $authService->clearIdentity();
    }    

}
