<?php
/**
 * BS_Worksheet extension
 * 
 * 
 * @category       BS
 * @package        BS_Worksheet
 * @copyright      Copyright (c) 2015
 */
/**
 * Worksheet edit form tab
 *
 * @category    BS
 * @package     BS_Worksheet
 * @author      Bui Phong
 */
class BS_Worksheet_Block_Adminhtml_Worksheet_Edit_Tab_Form extends Mage_Adminhtml_Block_Widget_Form
{
    /**
     * prepare the form
     *
     * @access protected
     * @return BS_Worksheet_Block_Adminhtml_Worksheet_Edit_Tab_Form
     * @author Bui Phong
     */
    protected function _prepareForm()
    {
        $form = new Varien_Data_Form();
        $form->setHtmlIdPrefix('worksheet_');
        $form->setFieldNameSuffix('worksheet');
        $this->setForm($form);
        $fieldset = $form->addFieldset(
            'worksheet_form',
            array('legend' => Mage::helper('bs_worksheet')->__('Worksheet'))
        );

        $fieldset->addType(
            'file',
            Mage::getConfig()->getBlockClassName('bs_worksheet/adminhtml_worksheet_helper_file')
        );

        $fieldset->addField(
            'ws_name',
            'text',
            array(
                'label' => Mage::helper('bs_worksheet')->__('Worksheet Name'),
                'name'  => 'ws_name',
            'required'  => true,
            'class' => 'required-entry',

           )
        );

        $fieldset->addField(
            'ws_code',
            'text',
            array(
                'label' => Mage::helper('bs_worksheet')->__('Worksheet Code'),
                'name'  => 'ws_code',
            'note'	=> $this->__('Do not input /YYZZ here'),

           )
        );

        $fieldset->addField(
            'ws_file',
            'file',
            array(
                'label' => Mage::helper('bs_worksheet')->__('Worksheet Content'),
                'name'  => 'ws_file',

            )
        );
        $fieldset->addField(
            'ws_pdf',
            'file',
            array(
                'label' => Mage::helper('bs_worksheet')->__('Worksheet Approved PDF'),
                'name'  => 'ws_pdf',

            )
        );

        $fieldset->addField(
            'ws_page',
            'text',
            array(
                'label' => Mage::helper('bs_worksheet')->__('Total Page'),
                'name'  => 'ws_page',

            )
        );

        $fieldset->addField(
            'ws_approved_date',
            'date',
            array(
                'label' => Mage::helper('bs_worksheet')->__('Approved Date'),
                'name'  => 'ws_approved_date',

            'image' => $this->getSkinUrl('images/grid-cal.gif'),
            'format'  => Mage::app()->getLocale()->getDateFormat(Mage_Core_Model_Locale::FORMAT_TYPE_SHORT),
                'value'              => date( Mage::app()->getLocale()->getDateStrFormat(Mage_Core_Model_Locale::FORMAT_TYPE_SHORT),
                    strtotime('next day') )
           )
        );

        $fieldset->addField(
            'ws_revision',
            'select',
            array(
                'label' => Mage::helper('bs_worksheet')->__('Revision'),
                'name'  => 'ws_revision',

            'values'=> Mage::getModel('bs_worksheet/worksheet_attribute_source_wsrevision')->getAllOptions(true),
           )
        );
        $fieldset->addField(
            'status',
            'select',
            array(
                'label'  => Mage::helper('bs_worksheet')->__('Status'),
                'name'   => 'status',
                'values' => array(
                    array(
                        'value' => 1,
                        'label' => Mage::helper('bs_worksheet')->__('Enabled'),
                    ),
                    array(
                        'value' => 0,
                        'label' => Mage::helper('bs_worksheet')->__('Disabled'),
                    ),
                ),
            )
        );
        $formValues = Mage::registry('current_worksheet')->getDefaultValues();
        if (!is_array($formValues)) {
            $formValues = array();
        }
        if (Mage::getSingleton('adminhtml/session')->getWorksheetData()) {
            $formValues = array_merge($formValues, Mage::getSingleton('adminhtml/session')->getWorksheetData());
            Mage::getSingleton('adminhtml/session')->setWorksheetData(null);
        } elseif (Mage::registry('current_worksheet')) {
            $formValues = array_merge($formValues, Mage::registry('current_worksheet')->getData());
        }
        $form->setValues($formValues);
        return parent::_prepareForm();
    }
}
