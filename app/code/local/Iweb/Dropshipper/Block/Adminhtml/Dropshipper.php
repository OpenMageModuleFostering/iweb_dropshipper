<?php
/**
 * Magento
 *
 * @category    Iweb
 * @package     Iweb_Dropshipper
*/
class Iweb_Dropshipper_Block_Adminhtml_Dropshipper extends Mage_Adminhtml_Block_Widget_Grid_Container
{
  public function __construct()
  {
    $this->_controller = 'adminhtml_dropshipper';
    $this->_blockGroup = 'iweb_dropshipper';
    $this->_headerText = Mage::helper('iweb_dropshipper')->__('Dropshipper Manager');
    $this->_addButtonLabel = Mage::helper('iweb_dropshipper')->__('Add');
    parent::__construct();
  }
}