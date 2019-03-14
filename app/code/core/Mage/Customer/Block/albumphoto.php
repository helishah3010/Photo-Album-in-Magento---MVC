<?php
class Mage_Customer_Block_Albumphoto extends Mage_Core_Block_Template {

    public function __construct() {
        parent::__construct();
        $this->setTemplate('customer/albumphoto/albumphoto.phtml');
        Mage::app()->getFrontController()->getAction()->getLayout()->getBlock('root')->setHeaderTitle(Mage::helper('customer')->__('albumphoto'));
    }

    public function getAlbumURL() {
        return $this->getUrl('customer/album');
    }
}?>
 ?>