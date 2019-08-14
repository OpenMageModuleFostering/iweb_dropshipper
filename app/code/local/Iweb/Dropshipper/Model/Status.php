<?php
/**
 * Magento
 *
 * @category    Iweb
 * @package     Iweb_Dropshipper
*/
class Iweb_Dropshipper_Model_Status extends Varien_Object
{
    const STATUS_ENABLED	= 1;
    const STATUS_DISABLED	= 2;

    static public function getOptionArray()
    {
        return array(
            self::STATUS_ENABLED    => Mage::helper('iweb_dropshipper')->__('Enabled'),
            self::STATUS_DISABLED   => Mage::helper('iweb_dropshipper')->__('Disabled')
        );
    }
}