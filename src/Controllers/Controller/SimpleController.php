<?php

namespace Controllers\Controller;

abstract class SimpleController extends \Zend\Mvc\Controller\AbstractActionController
{
    private $entityManager = null;
    private $authService = null;


    public function __construct(\Doctrine\ORM\EntityManager $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    public function setAuthService(\Zend\Authentication\AuthenticationServiceInterface $authService) {
        $this->authService = $authService;
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
    
    /**
     * Get entity manager
     * 
     * @return \Doctrine\ORM\EntityManager
     */
    public function getEntityManager()
    {
        return $this->entityManager;
    }

    public function getAuthService()
    {
        return $this->authService;
    }

    /**
     * Set entity manager
     *
     */
    public function setEntityManager(\Doctrine\ORM\EntityManager $entityManager)
    {
        $this->entityManager = $entityManager;
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
        if ($username === '') {
            return false;
        }

        if ($this->getAuthService() === null) {
            return false;
        }
        $authService = $this->getAuthService();
        $adapter = $this->getAuthService()->getAdapter();
        $adapter->setIdentity($username);
        $adapter->setCredential(md5($password));
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
        $authService = $this->getAuthService();
        $loggedUser = $authService->getIdentity();

        return $loggedUser;
    }

    public function logout()
    {
        $authService = $this->getAuthService();
        $authService->clearIdentity();
    }    

}
