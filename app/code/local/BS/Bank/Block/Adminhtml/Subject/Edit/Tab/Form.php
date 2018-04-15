<?php
/**
 * BS_Bank extension
 * 
 * @category       BS
 * @package        BS_Bank
 * @copyright      Copyright (c) 2015
 */
/**
 * Subject edit form tab
 *
 * @category    BS
 * @package     BS_Bank
 * @author      Bui Phong
 */
class BS_Bank_Block_Adminhtml_Subject_Edit_Tab_Form extends Mage_Adminhtml_Block_Widget_Form
{
    /**
     * prepare the form
     *
     * @access protected
     * @return BS_Bank_Block_Adminhtml_Subject_Edit_Tab_Form
     * @author Bui Phong
     */
    protected function _prepareForm()
    {
        $form = new Varien_Data_Form();
        $form->setHtmlIdPrefix('subject_');
        $form->setFieldNameSuffix('subject');
        $this->setForm($form);
        $fieldset = $form->addFieldset(
            'subject_form',
            array('legend' => Mage::helper('bs_bank')->__('Subject'))
        );

        $fieldset->addField(
            'subject_name',
            'text',
            array(
                'label' => Mage::helper('bs_bank')->__('Subject Name'),
                'name'  => 'subject_name',
            'required'  => true,
            'class' => 'required-entry',

           )
        );

        $fieldset->addField(
            'subject_code',
            'text',
            array(
                'label' => Mage::helper('bs_bank')->__('Subject Code'),
                'name'  => 'subject_code',
            'required'  => true,
            'class' => 'required-entry',

           )
        );

        $fieldset->addField(
            'subject_order',
            'text',
            array(
                'label' => Mage::helper('bs_bank')->__('Sort Order'),
                'name'  => 'subject_order',

           )
        );
        $fieldset->addField(
            'status',
            'select',
            array(
                'label'  => Mage::helper('bs_bank')->__('Status'),
                'name'   => 'status',
                'values' => array(
                    array(
                        'value' => 1,
                        'label' => Mage::helper('bs_bank')->__('Enabled'),
                    ),
                    array(
                        'value' => 0,
                        'label' => Mage::helper('bs_bank')->__('Disabled'),
                    ),
                ),
            )
        );
        $formValues = Mage::registry('current_subject')->getDefaultValues();
        if (!is_array($formValues)) {
            $formValues = array();
        }
        if (Mage::getSingleton('adminhtml/session')->getSubjectData()) {
            $formValues = array_merge($formValues, Mage::getSingleton('adminhtml/session')->getSubjectData());
            Mage::getSingleton('adminhtml/session')->setSubjectData(null);
        } elseif (Mage::registry('current_subject')) {
            $formValues = array_merge($formValues, Mage::registry('current_subject')->getData());
        }
        $form->setValues($formValues);
        return parent::_prepareForm();
    }
}
