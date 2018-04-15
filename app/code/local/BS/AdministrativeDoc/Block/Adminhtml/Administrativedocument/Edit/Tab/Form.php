<?php
/**
 * BS_AdministrativeDoc extension
 * 
 * @category       BS
 * @package        BS_AdministrativeDoc
 * @copyright      Copyright (c) 2015
 */
/**
 * Administrative Document edit form tab
 *
 * @category    BS
 * @package     BS_AdministrativeDoc
 * @author Bui Phong
 */
class BS_AdministrativeDoc_Block_Adminhtml_Administrativedocument_Edit_Tab_Form extends Mage_Adminhtml_Block_Widget_Form
{
    /**
     * prepare the form
     *
     * @access protected
     * @return BS_AdministrativeDoc_Block_Adminhtml_Administrativedocument_Edit_Tab_Form
     * @author Bui Phong
     */
    protected function _prepareForm()
    {
        $form = new Varien_Data_Form();
        $form->setHtmlIdPrefix('administrativedocument_');
        $form->setFieldNameSuffix('administrativedocument');
        $this->setForm($form);
        $fieldset = $form->addFieldset(
            'administrativedocument_form',
            array('legend' => Mage::helper('bs_administrativedoc')->__('Administrative Document'))
        );
        $fieldset->addType(
            'file',
            Mage::getConfig()->getBlockClassName('bs_administrativedoc/adminhtml_administrativedocument_helper_file')
        );

        $fieldset->addField(
            'doc_name',
            'text',
            array(
                'label' => Mage::helper('bs_administrativedoc')->__('Document Name'),
                'name'  => 'doc_name',
            'required'  => true,
            'class' => 'required-entry',

           )
        );

        $fieldset->addField(
            'doc_date',
            'date',
            array(
                'label' => Mage::helper('bs_administrativedoc')->__('Date'),
                'name'  => 'doc_date',

            'image' => $this->getSkinUrl('images/grid-cal.gif'),
            'format'  => Mage::app()->getLocale()->getDateFormat(Mage_Core_Model_Locale::FORMAT_TYPE_SHORT),
           )
        );

        $fieldset->addField(
            'doc_file',
            'file',
            array(
                'label' => Mage::helper('bs_administrativedoc')->__('File'),
                'name'  => 'doc_file',

           )
        );

        $fieldset->addField(
            'doc_not',
            'textarea',
            array(
                'label' => Mage::helper('bs_administrativedoc')->__('Note'),
                'name'  => 'doc_not',

           )
        );
        $fieldset->addField(
            'status',
            'select',
            array(
                'label'  => Mage::helper('bs_administrativedoc')->__('Status'),
                'name'   => 'status',
                'values' => array(
                    array(
                        'value' => 1,
                        'label' => Mage::helper('bs_administrativedoc')->__('Enabled'),
                    ),
                    array(
                        'value' => 0,
                        'label' => Mage::helper('bs_administrativedoc')->__('Disabled'),
                    ),
                ),
            )
        );
        $formValues = Mage::registry('current_administrativedocument')->getDefaultValues();
        if (!is_array($formValues)) {
            $formValues = array();
        }
        if (Mage::getSingleton('adminhtml/session')->getAdministrativedocumentData()) {
            $formValues = array_merge($formValues, Mage::getSingleton('adminhtml/session')->getAdministrativedocumentData());
            Mage::getSingleton('adminhtml/session')->setAdministrativedocumentData(null);
        } elseif (Mage::registry('current_administrativedocument')) {
            $formValues = array_merge($formValues, Mage::registry('current_administrativedocument')->getData());
        }
        $form->setValues($formValues);
        return parent::_prepareForm();
    }
}
