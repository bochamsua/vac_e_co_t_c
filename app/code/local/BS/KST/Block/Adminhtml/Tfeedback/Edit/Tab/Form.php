<?php
/**
 * BS_KST extension
 * 
 * @category       BS
 * @package        BS_KST
 * @copyright      Copyright (c) 2016
 */
/**
 * Trainee Feedback edit form tab
 *
 * @category    BS
 * @package     BS_KST
 * @author Bui Phong
 */
class BS_KST_Block_Adminhtml_Tfeedback_Edit_Tab_Form extends Mage_Adminhtml_Block_Widget_Form
{
    /**
     * prepare the form
     *
     * @access protected
     * @return BS_KST_Block_Adminhtml_Tfeedback_Edit_Tab_Form
     * @author Bui Phong
     */
    protected function _prepareForm()
    {
        $form = new Varien_Data_Form();
        $form->setHtmlIdPrefix('tfeedback_');
        $form->setFieldNameSuffix('tfeedback');
        $this->setForm($form);
        $fieldset = $form->addFieldset(
            'tfeedback_form',
            array('legend' => Mage::helper('bs_kst')->__('Trainee Feedback'))
        );

        $fieldset->addField(
            'content',
            'text',
            array(
                'label' => Mage::helper('bs_kst')->__('content'),
                'name'  => 'content',

           )
        );
        $fieldset->addField(
            'status',
            'select',
            array(
                'label'  => Mage::helper('bs_kst')->__('Status'),
                'name'   => 'status',
                'values' => array(
                    array(
                        'value' => 1,
                        'label' => Mage::helper('bs_kst')->__('Enabled'),
                    ),
                    array(
                        'value' => 0,
                        'label' => Mage::helper('bs_kst')->__('Disabled'),
                    ),
                ),
            )
        );
        $formValues = Mage::registry('current_tfeedback')->getDefaultValues();
        if (!is_array($formValues)) {
            $formValues = array();
        }
        if (Mage::getSingleton('adminhtml/session')->getTfeedbackData()) {
            $formValues = array_merge($formValues, Mage::getSingleton('adminhtml/session')->getTfeedbackData());
            Mage::getSingleton('adminhtml/session')->setTfeedbackData(null);
        } elseif (Mage::registry('current_tfeedback')) {
            $formValues = array_merge($formValues, Mage::registry('current_tfeedback')->getData());
        }
        $form->setValues($formValues);
        return parent::_prepareForm();
    }
}
