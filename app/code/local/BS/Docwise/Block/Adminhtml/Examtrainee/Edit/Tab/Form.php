<?php
/**
 * BS_Docwise extension
 * 
 * @category       BS
 * @package        BS_Docwise
 * @copyright      Copyright (c) 2015
 */
/**
 * Exam Trainee edit form tab
 *
 * @category    BS
 * @package     BS_Docwise
 * @author Bui Phong
 */
class BS_Docwise_Block_Adminhtml_Examtrainee_Edit_Tab_Form extends Mage_Adminhtml_Block_Widget_Form
{
    /**
     * prepare the form
     *
     * @access protected
     * @return BS_Docwise_Block_Adminhtml_Examtrainee_Edit_Tab_Form
     * @author Bui Phong
     */
    protected function _prepareForm()
    {
        $form = new Varien_Data_Form();
        $form->setHtmlIdPrefix('examtrainee_');
        $form->setFieldNameSuffix('examtrainee');
        $this->setForm($form);
        $fieldset = $form->addFieldset(
            'examtrainee_form',
            array('legend' => Mage::helper('bs_docwise')->__('Exam Trainee'))
        );

        $fieldset->addField(
            'exam_id',
            'text',
            array(
                'label' => Mage::helper('bs_docwise')->__('Exam'),
                'name'  => 'exam_id',
            'required'  => true,
            'class' => 'required-entry',

           )
        );

        $fieldset->addField(
            'trainee_id',
            'text',
            array(
                'label' => Mage::helper('bs_docwise')->__('Trainee'),
                'name'  => 'trainee_id',

           )
        );

        $fieldset->addField(
            'position',
            'text',
            array(
                'label' => Mage::helper('bs_docwise')->__('Position'),
                'name'  => 'position',

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
        $formValues = Mage::registry('current_examtrainee')->getDefaultValues();
        if (!is_array($formValues)) {
            $formValues = array();
        }
        if (Mage::getSingleton('adminhtml/session')->getExamtraineeData()) {
            $formValues = array_merge($formValues, Mage::getSingleton('adminhtml/session')->getExamtraineeData());
            Mage::getSingleton('adminhtml/session')->setExamtraineeData(null);
        } elseif (Mage::registry('current_examtrainee')) {
            $formValues = array_merge($formValues, Mage::registry('current_examtrainee')->getData());
        }
        $form->setValues($formValues);
        return parent::_prepareForm();
    }
}
