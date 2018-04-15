<?php
/**
 * BS_Bank extension
 * 
 * @category       BS
 * @package        BS_Bank
 * @copyright      Copyright (c) 2015
 */
/**
 * Answer edit form tab
 *
 * @category    BS
 * @package     BS_Bank
 * @author      Bui Phong
 */
class BS_Bank_Block_Adminhtml_Answer_Edit_Tab_Form extends Mage_Adminhtml_Block_Widget_Form
{
    /**
     * prepare the form
     *
     * @access protected
     * @return BS_Bank_Block_Adminhtml_Answer_Edit_Tab_Form
     * @author Bui Phong
     */
    protected function _prepareForm()
    {
        $form = new Varien_Data_Form();
        $form->setHtmlIdPrefix('answer_');
        $form->setFieldNameSuffix('answer');
        $this->setForm($form);
        $fieldset = $form->addFieldset(
            'answer_form',
            array('legend' => Mage::helper('bs_bank')->__('Answer'))
        );

        $fieldset->addField(
            'answer_answer',
            'textarea',
            array(
                'label' => Mage::helper('bs_bank')->__('Answer'),
                'name'  => 'answer_answer',
            'required'  => true,
            'class' => 'required-entry',

           )
        );

        $fieldset->addField(
            'question_id',
            'text',
            array(
                'label' => Mage::helper('bs_bank')->__('Question'),
                'name'  => 'question_id',
            'required'  => true,
            'class' => 'required-entry',

           )
        );

        $fieldset->addField(
            'answer_correct',
            'select',
            array(
                'label' => Mage::helper('bs_bank')->__('Correct'),
                'name'  => 'answer_correct',

            'values'=> array(
                array(
                    'value' => 1,
                    'label' => Mage::helper('bs_bank')->__('Yes'),
                ),
                array(
                    'value' => 0,
                    'label' => Mage::helper('bs_bank')->__('No'),
                ),
            ),
           )
        );

        $fieldset->addField(
            'answer_position',
            'text',
            array(
                'label' => Mage::helper('bs_bank')->__('Position'),
                'name'  => 'answer_position',

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
        $formValues = Mage::registry('current_answer')->getDefaultValues();
        if (!is_array($formValues)) {
            $formValues = array();
        }
        if (Mage::getSingleton('adminhtml/session')->getAnswerData()) {
            $formValues = array_merge($formValues, Mage::getSingleton('adminhtml/session')->getAnswerData());
            Mage::getSingleton('adminhtml/session')->setAnswerData(null);
        } elseif (Mage::registry('current_answer')) {
            $formValues = array_merge($formValues, Mage::registry('current_answer')->getData());
        }
        $form->setValues($formValues);
        return parent::_prepareForm();
    }
}
