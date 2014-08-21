<?php

namespace Controllers\Controller;

use Controllers\Controller\AbstractController;
use Zend\View\Model\ViewModel;

class DefaultController extends AbstractController
{
    private $_entity = '';
    private $_searchMethod = 'findAll';
    private $_searchParams = array();
    private $_form = '';
    private $_failRoute = 'home';
    private $_addTitle = 'Add item';
    private $_updateTitle = 'Update item';
    private $_deleteTitle = 'Delete item';
    private $_deleteDisplyaProperty = 'name';
    private $_formView = 'controllers/standart/form';
    private $_deleteView = 'controllers/standart/delete_dialog';
    

    public function indexAction()
    {
        $items = $this->getEntityManager()
                ->getRepository($this->_entity)
                ->$this->_searchMethod($this->_searchParams);

        return new ViewModel(array(
            'items' => $items,
            'route' => $this->_failRoute
        ));
    }

    public function addAction()
    {
        $item = new $this->_entity();
        $form = new $this->_form($this->getEntityManager());
        $form->bind($item);

        $request = $this->getRequest();
        if ($request->isPost()) {
            $form->setData($request->getPost());
            if ($form->isValid()) {
                $this->getEntityManager()->persist($item);
                $this->getEntityManager()->flush();

                return $this->redirect()->toRoute($this->_failRoute);
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
            return $this->redirect()->toRoute($this->_failRoute);
        }

        try {
            $item = $this->getEntityManager()
                    ->getRepository($this->_entity)
                    ->find($id);
        } catch (\Exception $ex) {
            return $this->redirect()->toRoute($this->_failRoute);
        }

        $form = new $this->_form($this->getEntityManager());
        $form->bind($item);

        $request = $this->getRequest();
        if ($request->isPost()) {
            $form->setData($request->getPost());
            if ($form->isValid()) {
                $this->getEntityManager()->flush();
                return $this->redirect()->toRoute($this->_failRoute);
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
            return $this->redirect()->toRoute($this->_failRoute);
        }

        try {
            $item = $this->getEntityManager()
                    ->getRepository($this->_entity)
                    ->find($id);
        } catch (\Exception $ex) {
            return $this->redirect()->toRoute($this->_failRoute);
        }

        $request = $this->getRequest();
        if ($request->isPost()) {
            $del = $request->getPost('del', 'No');

            if ($del == 'Yes') {
                $this->getEntityManager()->remove($item);
                $this->getEntityManager()->flush();
            }

            return $this->redirect()->toRoute($this->_failRoute);
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
