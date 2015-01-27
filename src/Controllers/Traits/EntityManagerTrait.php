<?php
namespace Controllers\Traits;

trait EntityManagerTrait
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
}
