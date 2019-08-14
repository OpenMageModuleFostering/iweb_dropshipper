<?php
/**
 * Magento
 *
 * @category    Iweb
 * @package     Iweb_Dropshipper
*/
class Iweb_Dropshipper_Model_Resource_Dropshipper extends Mage_Core_Model_Resource_Db_Abstract
{
    
    public function _construct()
    {    
        // Note that the dshipper_id refers to the key field in your database table.
        $this->_init('iweb_dropshipper/dropshipper', 'dshipper_id');
    }
}