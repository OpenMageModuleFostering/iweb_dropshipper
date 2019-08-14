<?php
/**
 * Magento
 *
 * @category    Iweb
 * @package     Iweb_Dropshipper
*/
class Iweb_Dropshipper_Block_Adminhtml_Dropshipper_Edit_Tab_Form extends Mage_Adminhtml_Block_Widget_Form
{
    
  protected function _prepareForm()
  {
      $form = new Varien_Data_Form();
      $this->setForm($form);
      $fieldset = $form->addFieldset('dropshipper_form', array('legend'=>Mage::helper('iweb_dropshipper')->__('Dropshipper information')));
     
      $fieldset->addField('name', 'text', array(
          'label'     => Mage::helper('iweb_dropshipper')->__('Supplier Name'),
          'class'     => 'required-entry',
          'required'  => true,
          'name'     => 'supplier_name'  
      ));
      
      $fieldset->addField('email', 'text', array(
          'label'     => Mage::helper('iweb_dropshipper')->__('Email'),
          'class'     => 'required-entry',
          'required'  => true,
          'name'      => 'email',
      ));
      
      $fieldset->addField('address', 'text', array(
          'label'     => Mage::helper('iweb_dropshipper')->__('Address'),
          'name'      => 'address',
      ));
      
      $fieldset->addField('city', 'text', array(
          'label'     => Mage::helper('iweb_dropshipper')->__('City'),
          'name'      => 'city',
      ));
      
      $fieldset->addField('state', 'text', array(
          'label'     => Mage::helper('iweb_dropshipper')->__('State'),
          'name'      => 'state',
      ));
      
      $fieldset->addField('country', 'text', array(
          'label'     => Mage::helper('iweb_dropshipper')->__('Country'),
          'name'      => 'country',
      ));
      
      $fieldset->addField('status', 'select', array(
          'label'     => Mage::helper('iweb_dropshipper')->__('Status'),
          'name'      => 'status',
          'values'    => array(
              array(
                  'value'     => 1,
                  'label'     => Mage::helper('iweb_dropshipper')->__('Enabled'),
              ),

              array(
                  'value'     => 2,
                  'label'     => Mage::helper('iweb_dropshipper')->__('Disabled'),
              ),
          ),
      ));
     
     
      if ( Mage::getSingleton('adminhtml/session')->getDropshipperData() )
      {
          $form->setValues(Mage::getSingleton('adminhtml/session')->getDropshipperData());
          Mage::getSingleton('adminhtml/session')->setDropshipperData(null);
      } elseif ( Mage::registry('dropshipper_data') ) {
          $form->setValues(Mage::registry('dropshipper_data')->getData());
      }
      return parent::_prepareForm();
  }
}
