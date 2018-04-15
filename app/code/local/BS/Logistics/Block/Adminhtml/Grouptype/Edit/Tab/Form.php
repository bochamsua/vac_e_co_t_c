<?php
/**
 * BS_Logistics extension
 * 
 * @category       BS
 * @package        BS_Logistics
 * @copyright      Copyright (c) 2016
 */
/**
 * Type edit form tab
 *
 * @category    BS
 * @package     BS_Logistics
 * @author Bui Phong
 */
class BS_Logistics_Block_Adminhtml_Grouptype_Edit_Tab_Form extends Mage_Adminhtml_Block_Widget_Form
{
    /**
     * prepare the form
     *
     * @access protected
     * @return BS_Logistics_Block_Adminhtml_Grouptype_Edit_Tab_Form
     * @author Bui Phong
     */
    protected function _prepareForm()
    {
        $form = new Varien_Data_Form();
        $form->setHtmlIdPrefix('grouptype_');
        $form->setFieldNameSuffix('grouptype');
        $this->setForm($form);
        $fieldset = $form->addFieldset(
            'grouptype_form',
            array('legend' => Mage::helper('bs_logistics')->__('Type'))
        );

        $fieldset->addField(
            'name',
            'text',
            array(
                'label' => Mage::helper('bs_logistics')->__('Name'),
                'name'  => 'name',
            'required'  => true,
            'class' => 'required-entry',

           )
        );

        $fieldset->addField(
            'name_vi',
            'text',
            array(
                'label' => Mage::helper('bs_logistics')->__('Vietnamese'),
                'name'  => 'name_vi',

           )
        );

        $fieldset->addField(
            'code',
            'text',
            array(
                'label' => Mage::helper('bs_logistics')->__('Code'),
                'name'  => 'code',
            'required'  => true,
            'class' => 'required-entry',

           )
        );

        $fieldset->addField(
            'note',
            'textarea',
            array(
                'label' => Mage::helper('bs_logistics')->__('Note'),
                'name'  => 'note',

           )
        );
        $fieldset->addField(
            'status',
            'select',
            array(
                'label'  => Mage::helper('bs_logistics')->__('Status'),
                'name'   => 'status',
                'values' => array(
                    array(
                        'value' => 1,
                        'label' => Mage::helper('bs_logistics')->__('Enabled'),
                    ),
                    array(
                        'value' => 0,
                        'label' => Mage::helper('bs_logistics')->__('Disabled'),
                    ),
                ),
            )
        );
        $formValues = Mage::registry('current_grouptype')->getDefaultValues();
        if (!is_array($formValues)) {
            $formValues = array();
        }
        if (Mage::getSingleton('adminhtml/session')->getGrouptypeData()) {
            $formValues = array_merge($formValues, Mage::getSingleton('adminhtml/session')->getGrouptypeData());
            Mage::getSingleton('adminhtml/session')->setGrouptypeData(null);
        } elseif (Mage::registry('current_grouptype')) {
            $formValues = array_merge($formValues, Mage::registry('current_grouptype')->getData());
        }
        $form->setValues($formValues);
        return parent::_prepareForm();
    }
}
