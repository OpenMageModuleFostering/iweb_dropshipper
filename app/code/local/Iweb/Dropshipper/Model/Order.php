<?php
/**
 * Magento
 *
 * @category    Iweb
 * @package     Iweb_Dropshipper
*/
class Iweb_Dropshipper_Model_Order extends Mage_Core_Model_Abstract
{
    public function _construct()
    {
        parent::_construct();
        $this->_init('iweb_dropshipper/order');
    }
    
    public function getDropshipperByItem($itemId)
    {
        $dropshipper = Mage::getModel('iweb_dropshipper/order')->getCollection();
        $dropshipper = $dropshipper->getItemByColumnValue('item_id',$itemId);
        return $dropshipper;
    }    
}
