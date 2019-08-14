<?php
/**
 * Magento
 *
 * @category    Iweb    
 * @package     Iweb_Dropshipper
*/
class Iweb_Dropshipper_Model_Dropshipper extends Mage_Core_Model_Abstract
{
    protected $_attributeOptionCollection = null;   
    protected $_attribute = null;
    public function _construct()
    {
        parent::_construct();
        $this->_init('iweb_dropshipper/dropshipper');
    }
    
    protected function _afterSave()
    {
        if($this->getProducts())
            $this->getProductInstance()->saveProductRelations($this);
            
        return parent::_afterSave();
    }
    
    protected function getProductInstance()
    {
        return Mage::getModel('iweb_dropshipper/product');
    }
}
