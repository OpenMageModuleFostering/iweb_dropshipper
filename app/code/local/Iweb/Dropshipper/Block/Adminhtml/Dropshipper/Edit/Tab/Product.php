<?php
/**
 * Magento
 * @category    Iweb
 * @package     Iweb_Dropshipper
 */

/**
 * Product in Dropshipper from
 *
 * @category    Iweb
 * @package     Iweb_Dropshipper
 */
class Iweb_Dropshipper_Block_Adminhtml_Dropshipper_Edit_Tab_Product extends Mage_Adminhtml_Block_Widget_Grid
{
    protected function _getStore()
    {
        $storeId = (int) $this->getRequest()->getParam('store', 0);
        return Mage::app()->getStore($storeId);
    }
    
    public function __construct()
    {
        parent::__construct();
        $this->setId('dropshipper_products');
        $this->setDefaultSort('entity_id');
        $this->setUseAjax(true);
        if ($this->getDropshipper()->getId()) {
            $this->setDefaultFilter(array('in_products' => 1));
        }
        if ($this->isReadonly()) {
            $this->setFilterVisibility(false);
        }
    }

    public function getDropshipper()
    {
        return Mage::registry('dropshipper_data');
    }
    
    protected function _addColumnFilterToCollection($column)
    {
         // Set custom filter for in product flag
        if ($column->getId() == 'in_products') {
            $productIds = $this->_getSelectedProducts();
            if (empty($productIds)) {
                $productIds = 0;
            }
            if ($column->getFilter()->getValue()) {
                $this->getCollection()->addFieldToFilter('entity_id', array('in' => $productIds));
            } else {
                if($productIds) {
                    $this->getCollection()->addFieldToFilter('entity_id', array('nin' => $productIds));
                }
            }
        } else {
            parent::_addColumnFilterToCollection($column);
        }
        return $this;
    }
    protected function _prepareCollection()
    {
        $store = $this->_getStore();
        $collection = Mage::getModel('catalog/product')->getCollection()
            ->addAttributeToSelect('name')
            ->addAttributeToSelect('sku')
            ->addAttributeToSelect('price')
            ->addAttributeToSelect('cost')
            ->addAttributeToFilter('type_id',Mage_Catalog_Model_Product_Type::TYPE_SIMPLE)
            ->addStoreFilter($this->getRequest()->getParam('store'));
            
        if (Mage::helper('catalog')->isModuleEnabled('Mage_CatalogInventory')) {
            $collection->joinField('qty',
                'cataloginventory/stock_item',
                'qty',
                'product_id=entity_id',
                '{{table}}.stock_id=1',
                'left');
        }
        
        $collection->joinAttribute('visibility', 'catalog_product/visibility', 'entity_id', null, 'inner', $store->getId());
        
        if ($this->isReadonly()) {
            $productIds = $this->_getSelectedProducts();
            if (empty($productIds)) {
                $productIds = array(0);
            }
            $collection->addFieldToFilter('entity_id', array('in' => $productIds));
        }
            
        $this->setCollection($collection);
        return parent::_prepareCollection();
    }
    
    /**
     * Checks when this block is readonly
     *
     * @return boolean
     */
    public function isReadonly()
    {
        return false;
    }
    protected function _prepareColumns()
    {
        if (!$this->isReadonly()) {
            $this->addColumn('in_products', array(
                'header_css_class'  => 'a-center',
                'type'              => 'checkbox',
                'name'              => 'in_products',
                'values'            => $this->_getSelectedProducts(),
                'align'             => 'center',
                'index'             => 'entity_id'
            ));
        }
        
        $this->addColumn('entity_id', array(
            'header'    => Mage::helper('iweb_dropshipper')->__('ID'),
            'sortable'  => true,
            'width'     => '60',
            'index'     => 'entity_id'
        ));
        
        $this->addColumn('name', array(
            'header'    => Mage::helper('iweb_dropshipper')->__('Name'),
            'index'     => 'name'
        ));
        $this->addColumn('sku', array(
            'header'    => Mage::helper('iweb_dropshipper')->__('SKU'),
            'width'     => '80',
            'index'     => 'sku'
        ));
        $this->addColumn('type',
            array(
                'header'=> Mage::helper('iweb_dropshipper')->__('Type'),
                'width' => '60px',
                'index' => 'type_id',
                'type'  => 'options',
                'options' => Mage::getSingleton('catalog/product_type')->getOptionArray()
        ));
        
        $this->addColumn('visibility',
            array(
                'header'=> Mage::helper('catalog')->__('Visibility'),
                'width' => '70px',
                'index' => 'visibility',
                'type'  => 'options',
                'options' => Mage::getModel('catalog/product_visibility')->getOptionArray(),
        ));
        
        $this->addColumn('price', array(
            'header'    => Mage::helper('iweb_dropshipper')->__('Price'),
            'type'  => 'currency',
            'width'     => '1',
            'renderer'  => 'Iweb_Dropshipper_Block_Adminhtml_Widget_Grid_Column_Renderer_Input',
            'currency_code' => (string) Mage::getStoreConfig(Mage_Directory_Model_Currency::XML_PATH_CURRENCY_BASE),
            'index'     => 'price'
        ));
        
        $this->addColumn('cost', array(
            'header'    => Mage::helper('iweb_dropshipper')->__('Cost'),
            'type'  => 'currency',
            'width'     => '1',
            'renderer'  => 'Iweb_Dropshipper_Block_Adminhtml_Widget_Grid_Column_Renderer_Input',
            'currency_code' => (string) Mage::getStoreConfig(Mage_Directory_Model_Currency::XML_PATH_CURRENCY_BASE),
            'index'     => 'cost'
        ));
        
        $this->addColumn('qty', array(
          'header'    => Mage::helper('iweb_dropshipper')->__('STOCK'),
          'renderer'  => 'Iweb_Dropshipper_Block_Adminhtml_Widget_Grid_Column_Renderer_Input',
          'width'     => '1',
          'index'     => 'qty',
        ));

        return parent::_prepareColumns();
    }

    public function getGridUrl()
    {
        return $this->getUrl('*/*/productGrid', array('_current'=>true));
    }
 
    protected function _getSelectedProducts()
    {
        $products = $this->getProductsSelected();
        return $products;
    }
    
    protected function getProductsSelected()
    {
        
        if(Mage::registry('dropshipper_data')->getDshipper_id()){ 
            $products = Mage::getModel('catalog/product')->getCollection();
            $dshipper_id = Mage::registry('dropshipper_data')->getDshipper_id();        
            //$products->addAttributeToFilter('visibility',array('neq'=>1));
            $products->getSelect()
                     ->join(
                         array('dshipproduct'=> $products->getTable('iweb_dropshipper/product')),
                         'e.entity_id = dshipproduct.product_id AND dshipproduct.dshipper_id = '.$dshipper_id
                     );
            Mage::getSingleton('catalog/product_status')->addVisibleFilterToCollection($products);
        
            $products = $products->getAllIds();  
        
        }else{
            $products = null;
        }
       
        return $products;
    }

   

}

