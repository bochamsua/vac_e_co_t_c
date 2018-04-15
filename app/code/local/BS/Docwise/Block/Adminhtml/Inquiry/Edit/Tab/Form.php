<?php
/**
 * BS_Docwise extension
 * 
 * @category       BS
 * @package        BS_Docwise
 * @copyright      Copyright (c) 2015
 */
/**
 * Inquiry edit form tab
 *
 * @category    BS
 * @package     BS_Docwise
 * @author Bui Phong
 */
class BS_Docwise_Block_Adminhtml_Inquiry_Edit_Tab_Form extends Mage_Adminhtml_Block_Widget_Form
{
    /**
     * prepare the form
     *
     * @access protected
     * @return BS_Docwise_Block_Adminhtml_Inquiry_Edit_Tab_Form
     * @author Bui Phong
     */
    protected function _prepareForm()
    {
        $form = new Varien_Data_Form();
        $form->setHtmlIdPrefix('inquiry_');
        $form->setFieldNameSuffix('inquiry');
        $this->setForm($form);
        $fieldset = $form->addFieldset(
            'inquiry_form',
            array('legend' => Mage::helper('bs_docwise')->__('Inquiry'))
        );

        $fieldset->addField(
            'vaeco_id',
            'text',
            array(
                'label' => Mage::helper('bs_docwise')->__('VAECO ID'),
                'name'  => 'vaeco_id',

           )
        );
        $fieldset->addField(
            'url_key',
            'text',
            array(
                'label' => Mage::helper('bs_docwise')->__('Url key'),
                'name'  => 'url_key',
                'note'  => Mage::helper('bs_docwise')->__('Relative to Website Base URL')
            )
        );
        $fieldset->addField(
            'status',
            'select',
            array(
                'label'  => Mage::helper('bs_docwise')->__('Status'),
                'name'   => 'status',
                'values' => array(
                    array(
                        'value' => 1,
                        'label' => Mage::helper('bs_docwise')->__('Enabled'),
                    ),
                    array(
                        'value' => 0,
                        'label' => Mage::helper('bs_docwise')->__('Disabled'),
                    ),
                ),
            )
        );
        $formValues = Mage::registry('current_inquiry')->getDefaultValues();
        if (!is_array($formValues)) {
            $formValues = array();
        }
        if (Mage::getSingleton('adminhtml/session')->getInquiryData()) {
            $formValues = array_merge($formValues, Mage::getSingleton('adminhtml/session')->getInquiryData());
            Mage::getSingleton('adminhtml/session')->setInquiryData(null);
        } elseif (Mage::registry('current_inquiry')) {
            $formValues = array_merge($formValues, Mage::registry('current_inquiry')->getData());
        }
        $form->setValues($formValues);
        return parent::_prepareForm();
    }
}
