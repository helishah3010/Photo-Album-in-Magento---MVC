<?php
class Mage_Customer_Block_Album extends Mage_Core_Block_Template {

    public function __construct() {
        parent::__construct();
        $this->setTemplate('customer/album/album.phtml');
        Mage::app()->getFrontController()->getAction()->getLayout()->getBlock('root')->setHeaderTitle(Mage::helper('customer')->__('album'));
    }

    public function getAlbumEditURL() {
        return $this->getUrl('customer/albumphoto');
    }
}?>