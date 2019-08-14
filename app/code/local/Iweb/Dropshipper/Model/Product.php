<?php
/**
 * Magento
 *
 * @category    Iweb
 * @package     Iweb_Dropshipper
*/
class Iweb_Dropshipper_Model_Product extends Mage_Core_Model_Abstract
{
    protected $_allowAttributes = array(
        'price','cost','qty'
    );
    
    public function _construct()
    {
        parent::_construct();
        $this->_init('iweb_dropshipper/product');
    }
    
    public function saveProductRelations($dropshipper)
    {
        $this->getResource()->saveProductRelations($dropshipper);
    }
    
    public function updateProducts($object)
    {       
        $productData = $object['update_data'];
        
        $newProductArray = array();
        foreach($productData as $key=>$value)
        {
            foreach($value as $id=>$_val)
            {
                $newProductArray[$id][$key] = $_val;
            }            
        }
        
        foreach($newProductArray as $productId=>$val)
        {
            $product = Mage::getModel('catalog/product');
            $product->setStoreId(Mage_Core_Model_App::ADMIN_STORE_ID)
                        ->load($productId);    
            
            $stockItem = Mage::getModel('cataloginventory/stock_item')->loadByProduct($productId);
                  
            foreach($this->_allowAttributes as $_attribute)
            {
                if($val[$_attribute] != '')
                {
                    if($_attribute == 'qty')
                    {
                        $stockItem->setData('qty', $val[$_attribute]);
                        $stockItem->save();
                    }else{
                        $func = 'set'.ucfirst($_attribute); 
                        $product->$func($val[$_attribute]);
                        $product->save();
                    }
                     
                    
                }
            }
        }
    }
    
    public function getDropshipper($product){
        $dropShipperData = Mage::getModel('iweb_dropshipper/product')->load($product->getId(), 'product_id');
        return $dropShipperData;
    }
    
    public function updateProductPrice($dropshipperId,$percentage)
    {        
        $this->getResource()->updateProductPrice($dropshipperId,$percentage);
    }
}
