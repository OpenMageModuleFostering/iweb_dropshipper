<?php
/**
 * Magento
 *
 * @category    Iweb
 * @package     Iweb_Dropshipper
*/
$installer = $this;
$installer->startSetup();

/**
 * Create table iweb_dropshipper/dropshipper
 */
 
$table = $installer->getConnection()
    ->newTable($installer->getTable('iweb_dropshipper/dropshipper'))
    ->addColumn('dshipper_id', Varien_Db_Ddl_Table::TYPE_INTEGER, null, array(
        'identity'  => true,
        'unsigned'  => true,
        'nullable'  => false,
        'primary'   => true,
        ), 'Auto Increment ID')
    ->addColumn('name', Varien_Db_Ddl_Table::TYPE_VARCHAR, 255, array(
        'unsigned'  => true,
        'nullable'  => false,
        ), 'Name')
    ->addColumn('email', Varien_Db_Ddl_Table::TYPE_VARCHAR, 255, array(
        'unsigned'  => true,
        'nullable'  => false,
        ), 'Dropshipper Email')
     ->addColumn('address', Varien_Db_Ddl_Table::TYPE_VARCHAR, 255, array(
        'unsigned'  => true,
        'nullable'  => false,
        ), 'Address')
        ->addColumn('city', Varien_Db_Ddl_Table::TYPE_VARCHAR, 50, array(
        'unsigned'  => true,
        'nullable'  => false,
        ), 'City')
      ->addColumn('state', Varien_Db_Ddl_Table::TYPE_VARCHAR, 50, array(
        'unsigned'  => true,
        'nullable'  => false,
        ), 'State')
        ->addColumn('country', Varien_Db_Ddl_Table::TYPE_VARCHAR, 10, array(
        'unsigned'  => true,
        'nullable'  => false,
        ), 'Country')
         ->addColumn('zip', Varien_Db_Ddl_Table::TYPE_VARCHAR, 20, array(
        'unsigned'  => true,
        'nullable'  => false,
        ), 'Zip')
        ->addColumn('status', Varien_Db_Ddl_Table::TYPE_SMALLINT, null, array(
        'unsigned'  => true,
        'nullable'  => false,
        'default'   => '2',
        ), 'Status')
    ->setComment('Dropshipper Main Table');
    
$installer->getConnection()->createTable($table);

/**
 * Create table iweb_dropshipper/product
 */
$table = $installer->getConnection()
    ->newTable($installer->getTable('iweb_dropshipper/product'))
    ->addColumn('dshipper_product_id', Varien_Db_Ddl_Table::TYPE_INTEGER, 10, array(
        'identity'  => true,
        'unsigned'  => true,
        'nullable'  => false,
        'primary'   => true,
        ), 'Auto Increment ID')
    ->addColumn('product_id', Varien_Db_Ddl_Table::TYPE_INTEGER, null, array(
        'unsigned'  => true,
        'nullable'  => false,
        ), 'Product Id')
    ->addColumn('dshipper_id', Varien_Db_Ddl_Table::TYPE_INTEGER, null, array(
        'unsigned'  => true,
        'nullable'  => false,
        ), 'Reference of Dropshipper ID')
    ->addIndex(
        'UNIQUE_PRODUCT_DROPSHIPPER',
        array('product_id', 'dshipper_id'),
        array('type' => Varien_Db_Adapter_Interface::INDEX_TYPE_UNIQUE))
    ->addForeignKey(
        'FK_PRODUCT_DROPSHIPPER_ID',
        'dshipper_id', $installer->getTable('iweb_dropshipper/dropshipper'), 'dshipper_id',
        Varien_Db_Ddl_Table::ACTION_CASCADE, Varien_Db_Ddl_Table::ACTION_CASCADE)
    ->addForeignKey(
        'FK_CATALOG_PRODUCT_DROPSHIPPER',
        'product_id', $installer->getTable('catalog/product'), 'entity_id',
        Varien_Db_Ddl_Table::ACTION_CASCADE, Varien_Db_Ddl_Table::ACTION_CASCADE)
    ->setComment('DROPSHIPPER Product Table');
    
$installer->getConnection()->createTable($table);

/**
 * Create table iweb_dropshipper/order
 */
 
$table = $installer->getConnection()
    ->newTable($installer->getTable('iweb_dropshipper/order'))
    ->addColumn('dshipper_item_id', Varien_Db_Ddl_Table::TYPE_INTEGER, 10, array(
        'identity'  => true,
        'unsigned'  => true,
        'nullable'  => false,
        'primary'   => true,
        ), 'Auto Increment ID')
     ->addColumn('item_id', Varien_Db_Ddl_Table::TYPE_INTEGER, 10, array(
        'unsigned'  => true,
        'nullable'  => false,
        ), 'Item Id')
    ->addColumn('dshipper_id', Varien_Db_Ddl_Table::TYPE_INTEGER, null, array(
        'unsigned'  => true,
        'nullable'  => false,
        ), 'Dropshipper Id')
     ->addColumn('mail_status', Varien_Db_Ddl_Table::TYPE_SMALLINT, null, array(
        'unsigned'  => true,
        'nullable'  => false,
        ), 'Mail Status')
     ->addIndex(
        'UNIQUE_ORDER_DROPSHIPPER',
        array('item_id', 'dshipper_id'),
        array('type' => Varien_Db_Adapter_Interface::INDEX_TYPE_UNIQUE))
    ->addForeignKey(
        'FK_ORDER_DROPSHIPPER_ID',
        'dshipper_id', $installer->getTable('iweb_dropshipper/dropshipper'), 'dshipper_id',
        Varien_Db_Ddl_Table::ACTION_CASCADE, Varien_Db_Ddl_Table::ACTION_CASCADE)
    ->addForeignKey(
        'FK_SALES_ORDER_ITEM_DROPSHIPPER',
        'item_id', $installer->getTable('sales/order_item'), 'item_id',
        Varien_Db_Ddl_Table::ACTION_CASCADE, Varien_Db_Ddl_Table::ACTION_CASCADE)
    ->setComment('Dropshipper order Table');
$installer->getConnection()->createTable($table);

$installer->endSetup();

