<?php
/**
 * BS_WorksheetDoc extension
 * 
 * @category       BS
 * @package        BS_WorksheetDoc
 * @copyright      Copyright (c) 2015
 */
/**
 * Worksheet Document edit form tab
 *
 * @category    BS
 * @package     BS_WorksheetDoc
 * @author      Bui Phong
 */
class BS_WorksheetDoc_Block_Adminhtml_Worksheetdoc_Edit_Tab_Form extends Mage_Adminhtml_Block_Widget_Form
{
    /**
     * prepare the form
     *
     * @access protected
     * @return BS_WorksheetDoc_Block_Adminhtml_Worksheetdoc_Edit_Tab_Form
     * @author Bui Phong
     */
    protected function _prepareForm()
    {
        $form = new Varien_Data_Form();
        $form->setHtmlIdPrefix('worksheetdoc_');
        $form->setFieldNameSuffix('worksheetdoc');
        $this->setForm($form);
        $fieldset = $form->addFieldset(
            'worksheetdoc_form',
            array('legend' => Mage::helper('bs_worksheetdoc')->__('Worksheet Document'))
        );
        $fieldset->addType(
            'file',
            Mage::getConfig()->getBlockClassName('bs_worksheetdoc/adminhtml_worksheetdoc_helper_file')
        );

        $fieldset->addField(
            'wsdoc_name',
            'text',
            array(
                'label' => Mage::helper('bs_worksheetdoc')->__('Document Name'),
                'name'  => 'wsdoc_name',
            'required'  => true,
            'class' => 'required-entry',

           )
        );

        $disabled = false;
        if(Mage::registry('current_worksheetdoc')->getId()){
            $disabled = true;
        }

        $fieldset->addField(
            'wsdoc_type',
            'select',
            array(
                'label' => Mage::helper('bs_worksheetdoc')->__('Document Type'),
                'name'  => 'wsdoc_type',

                'values'=> Mage::getModel('bs_worksheetdoc/worksheetdoc_attribute_source_wsdoctype')->getAllOptions(true),
                'disabled'  => $disabled
           )
        );

        $fieldset->addField(
            'wsdoc_file',
            'file',
            array(
                'label' => Mage::helper('bs_worksheetdoc')->__('File'),
                'name'  => 'wsdoc_file',

           )
        );

        $fieldset->addField(
            'wsdoc_rev',
            'select',
            array(
                'label' => Mage::helper('bs_worksheetdoc')->__('Revision'),
                'name'  => 'wsdoc_rev',

            'values'=> Mage::getModel('bs_worksheetdoc/worksheetdoc_attribute_source_wsdocrev')->getAllOptions(true),
           )
        );
        $fieldset->addField(
            'status',
            'select',
            array(
                'label'  => Mage::helper('bs_worksheetdoc')->__('Status'),
                'name'   => 'status',
                'values' => array(
                    array(
                        'value' => 1,
                        'label' => Mage::helper('bs_worksheetdoc')->__('Enabled'),
                    ),
                    array(
                        'value' => 0,
                        'label' => Mage::helper('bs_worksheetdoc')->__('Disabled'),
                    ),
                ),
            )
        );

        $wsId = $this->getRequest()->getParam('worksheet_id', false);
        $fieldset->addField(
            'hidden_worksheet_id',
            'text',
            array(
                'label' => Mage::helper('bs_worksheetdoc')->__('Hidden Worksheet Id'),
                'name'  => 'hidden_worksheet_id',


            )
        );

        $formValues = Mage::registry('current_worksheetdoc')->getDefaultValues();
        if (!is_array($formValues)) {
            $formValues = array();
        }
        if (Mage::getSingleton('adminhtml/session')->getWorksheetdocData()) {
            $formValues = array_merge($formValues, Mage::getSingleton('adminhtml/session')->getWorksheetdocData());
            Mage::getSingleton('adminhtml/session')->setWorksheetdocData(null);
        } elseif (Mage::registry('current_worksheetdoc')) {
            $formValues = array_merge($formValues, Mage::registry('current_worksheetdoc')->getData());
        }

        if($wsId){
            $formValues = array_merge($formValues, array('hidden_worksheet_id' => $wsId));
        }

        $form->setValues($formValues);
        return parent::_prepareForm();
    }
}
