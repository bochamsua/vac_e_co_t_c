<?php
/**
 * BS_Docwise extension
 * 
 * @category       BS
 * @package        BS_Docwise
 * @copyright      Copyright (c) 2015
 */
/**
 * Import Trainee edit form tab
 *
 * @category    BS
 * @package     BS_Docwise
 * @author Bui Phong
 */
class BS_Docwise_Block_Adminhtml_Importtrainee_Edit_Tab_Form extends Mage_Adminhtml_Block_Widget_Form
{
    /**
     * prepare the form
     *
     * @access protected
     * @return BS_Docwise_Block_Adminhtml_Importtrainee_Edit_Tab_Form
     * @author Bui Phong
     */
    protected function _prepareForm()
    {
        $form = new Varien_Data_Form();
        $form->setHtmlIdPrefix('importtrainee_');
        $form->setFieldNameSuffix('importtrainee');
        $this->setForm($form);
        $fieldset = $form->addFieldset(
            'importtrainee_form',
            array('legend' => Mage::helper('bs_docwise')->__('Import Trainee'))
        );

        $fieldset->addField(
            'vaeco_ids',
            'textarea',
            array(
                'label' => Mage::helper('bs_docwise')->__('VAECO IDs'),
                'name'  => 'vaeco_ids',

           )
        );

        $fieldset->addField(
            'clearall',
            'select',
            array(
                'label' => Mage::helper('bs_docwise')->__('Clear all current?'),
                'name'  => 'clearall',

            'values'=> array(
                array(
                    'value' => 1,
                    'label' => Mage::helper('bs_docwise')->__('Yes'),
                ),
                array(
                    'value' => 0,
                    'label' => Mage::helper('bs_docwise')->__('No'),
                ),
            ),
           )
        );

        $fieldset->addField(
            'exam_id',
            'text',
            array(
                'label' => Mage::helper('bs_docwise')->__('Exam Id'),
                'name'  => 'exam_id',
                'readonly'=>true

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
        $formValues = Mage::registry('current_importtrainee')->getDefaultValues();
        if (!is_array($formValues)) {
            $formValues = array();
        }
        if (Mage::getSingleton('adminhtml/session')->getImporttraineeData()) {
            $formValues = array_merge($formValues, Mage::getSingleton('adminhtml/session')->getImporttraineeData());
            Mage::getSingleton('adminhtml/session')->setImporttraineeData(null);
        } elseif (Mage::registry('current_importtrainee')) {
            $formValues = array_merge($formValues, Mage::registry('current_importtrainee')->getData());

        }
        $data = array('exam_id' => $this->getRequest()->getParam('exam'));
        $formValues = array_merge($formValues, $data);

        $form->setValues($formValues);
        return parent::_prepareForm();
    }
}
