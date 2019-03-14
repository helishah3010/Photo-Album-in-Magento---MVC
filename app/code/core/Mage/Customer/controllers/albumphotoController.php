<?php
class Mage_Customer_AlbumphotoController extends Mage_Core_Controller_Front_Action {

    public function indexAction() {
        if (!$this->_getSession()->isLoggedIn()) {
            $this->_redirect('customer/account/login');
            return;
        }
        $this->loadLayout();
        $this->getLayout()->getBlock('head')->setTitle($this->__('albumphoto'));
        $this->renderLayout();
    }

    public function storeAction() {
        if (!$this->_getSession()->isLoggedIn()) {
            $this->_redirect('customer/account/login');
            return;
        }
        $this->loadLayout();
        $this->renderLayout();
    }

    protected function _getSession() {
        return Mage::getSingleton('customer/session');
    }
}?>