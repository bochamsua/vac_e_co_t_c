<?php
/**
 * BS_Report extension
 * 
 * @category       BS
 * @package        BS_Report
 * @copyright      Copyright (c) 2015
 */
/**
 * Manage edit form tab
 *
 * @category    BS
 * @package     BS_Report
 * @author Bui Phong
 */
class BS_Report_Block_Adminhtml_Manage_Edit_Tab_Form extends Mage_Adminhtml_Block_Widget_Form
{
    /**
     * prepare the form
     *
     * @access protected
     * @return BS_Report_Block_Adminhtml_Manage_Edit_Tab_Form
     * @author Bui Phong
     */
    protected function _prepareForm()
    {
        $form = new Varien_Data_Form();
        $form->setHtmlIdPrefix('manage_');
        $form->setFieldNameSuffix('manage');
        $this->setForm($form);
        $fieldset = $form->addFieldset(
            'manage_form',
            array('legend' => Mage::helper('bs_report')->__('Manage'))
        );

        $fieldset->addField(
            'manage',
            'text',
            array(
                'label' => Mage::helper('bs_report')->__('manage'),
                'name'  => 'manage',

           )
        );
        $fieldset->addField(
            'status',
            'select',
            array(
                'label'  => Mage::helper('bs_report')->__('Status'),
                'name'   => 'status',
                'values' => array(
                    array(
                        'value' => 1,
                        'label' => Mage::helper('bs_report')->__('Enabled'),
                    ),
                    array(
                        'value' => 0,
                        'label' => Mage::helper('bs_report')->__('Disabled'),
                    ),
                ),
            )
        );
        $formValues = Mage::registry('current_manage')->getDefaultValues();
        if (!is_array($formValues)) {
            $formValues = array();
        }
        if (Mage::getSingleton('adminhtml/session')->getManageData()) {
            $formValues = array_merge($formValues, Mage::getSingleton('adminhtml/session')->getManageData());
            Mage::getSingleton('adminhtml/session')->setManageData(null);
        } elseif (Mage::registry('current_manage')) {
            $formValues = array_merge($formValues, Mage::registry('current_manage')->getData());
        }
        $form->setValues($formValues);
        return parent::_prepareForm();
    }
}
