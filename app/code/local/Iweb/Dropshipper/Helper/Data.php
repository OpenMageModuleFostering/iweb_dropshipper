<?php
/**
 * Magento
 *
 * @category    Iweb
 * @package     Iweb_Dropshipper
*/
class Iweb_Dropshipper_Helper_Data extends Mage_Core_Helper_Abstract
{
    public function isActiveDropshipper()
    {
        return Mage::getStoreConfig('dropshipper/general/enabled');
    }
}