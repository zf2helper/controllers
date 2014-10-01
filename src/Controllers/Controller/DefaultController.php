<?php

namespace Controllers\Controller;

use Controllers\Controller\SimpleController;
use Zend\View\Model\ViewModel;

class DefaultController extends SimpleController
{

    protected $_title = 'Title';
    protected $_entity = '';
    protected $_searchParams = array();
    protected $_form = '';
    protected $_failRoute = '';
    protected $_addTitle = 'Add item';
    protected $_updateTitle = 'Update item';
    protected $_deleteTitle = 'Delete item';
    protected $_deleteDisplyaProperty = 'name';
    protected $_formView = 'controllers/standart/form';
    protected $_deleteView = 'controllers/standart/delete_dialog';

    protected function _preSearchParams($params)
    {

    }
    
    protected function _preCreateForm($form)
    {
        
    }

    protected function _preCreateItem($entity)
    {

    }

    protected function _postCreateItem($entity)
    {

    }
    
    protected function _preUpdateForm($form)
    {
        
    }    

    protected function _preUpdateItem($entity)
    {

    }

    protected function _postUpdateItem($entity)
    {

    }

    protected function _preDeleteItem($entity)
    {

    }

    protected function _postDeleteItem($entity)
    {

    }

    protected function _redirectBack()
    {
        return $this->redirect()->toRoute($this->_failRoute);
    }
    
    public function indexAction()
    {
        $this->_preSearchParams($this->_searchParams);

        $items = $this->getEntityManager()
                ->getRepository($this->_entity)
                ->findBy($this->_searchParams);

        return new ViewModel(array(
            'addLabel' => $this->_addTitle,
            'title' => $this->_title,
            'items' => $items,
            'route' => $this->_failRoute
        ));
    }

    public function addAction()
    {
        $item = new $this->_entity();
        $form = new $this->_form($this->getEntityManager());
        $form->bind($item);
        $this->_preCreateForm($form);

        $request = $this->getRequest();
        if ($request->isPost()) {
            $form->setData($request->getPost());
            if ($form->isValid()) {
                $this->_preCreateItem($item);
                $this->getEntityManager()->persist($item);
                $this->getEntityManager()->flush();
                $this->_postCreateItem($item);

                return $this->_redirectBack();
            }
        }

        $view = new ViewModel(array(
            'form' => $form,
            'title' => $this->_addTitle
        ));
        $view->setTemplate($this->_formView);

        return $view;
    }

    public function updateAction()
    {
        $id = $this->params()->fromRoute('id', false);

        if (!$id) {
            return $this->_redirectBack();
        }

        try {
            $item = $this->getEntityManager()
                    ->getRepository($this->_entity)
                    ->find($id);
        } catch (\Exception $ex) {
            return $this->_redirectBack();
        }

        $form = new $this->_form($this->getEntityManager());
        $form->bind($item);
        $form->get('submit')->setValue($this->_updateTitle);
        $this->_preUpdateForm($form);

        $request = $this->getRequest();
        if ($request->isPost()) {
            $form->setData($request->getPost());
            if ($form->isValid()) {
                $this->_preUpdateItem($item);
                $this->getEntityManager()->flush();
                $this->_postUpdateItem($item);
                return $this->_redirectBack();
            }
        }

        $view = new ViewModel(array(
            'form' => $form,
            'title' => $this->_updateTitle
        ));
        $view->setTemplate($this->_formView);

        return $view;
    }

    public function deleteAction()
    {
        $id = (int) $this->params()->fromRoute('id', 0);
        if (!$id) {
            return $this->_redirectBack();
        }

        try {
            $item = $this->getEntityManager()
                    ->getRepository($this->_entity)
                    ->find($id);
        } catch (\Exception $ex) {
            return $this->_redirectBack();
        }

        $request = $this->getRequest();
        if ($request->isPost()) {
            $del = $request->getPost('del', 'No');

            if ($del == 'Yes') {
                $this->_preDeleteItem($item);
                $this->getEntityManager()->remove($item);
                $this->getEntityManager()->flush();
                $this->_postDeleteItem($item);
            }

            return $this->_redirectBack();
        }

        $view = new ViewModel(array(
            'item' => $item,
            'title' => $this->_deleteTitle,
            'displayName' => $this->_deleteDisplyaProperty
        ));
        $view->setTemplate($this->_deleteView);

        return $view;
    }

}
