<?php
/**
 * BS_KST extension
 * 
 * @category       BS
 * @package        BS_KST
 * @copyright      Copyright (c) 2016
 */
/**
 * Instructor Feedback edit form tab
 *
 * @category    BS
 * @package     BS_KST
 * @author Bui Phong
 */
class BS_KST_Block_Adminhtml_Ifeedback_Edit_Tab_Form extends Mage_Adminhtml_Block_Widget_Form
{
    /**
     * prepare the form
     *
     * @access protected
     * @return BS_KST_Block_Adminhtml_Ifeedback_Edit_Tab_Form
     * @author Bui Phong
     */
    protected function _prepareForm()
    {
        $form = new Varien_Data_Form();
        $form->setHtmlIdPrefix('ifeedback_');
        $form->setFieldNameSuffix('ifeedback');
        $this->setForm($form);
        $fieldset = $form->addFieldset(
            'ifeedback_form',
            array('legend' => Mage::helper('bs_kst')->__('Instructor Feedback'))
        );

        $fieldset->addField(
            'task_id',
            'text',
            array(
                'label' => Mage::helper('bs_kst')->__('Task'),
                'name'  => 'task_id',

           )
        );

        $fieldset->addField(
            'trainee_id',
            'text',
            array(
                'label' => Mage::helper('bs_kst')->__('Trainee'),
                'name'  => 'trainee_id',

           )
        );

        $fieldset->addField(
            'criteria_one',
            'select',
            array(
                'label' => Mage::helper('bs_kst')->__('Skill of using, lookup data maintenance'),
                'name'  => 'criteria_one',
                'values'=> Mage::getModel('bs_kst/ifeedback_attribute_source_criteriaone')->getAllOptions(true),
           )
        );

        $fieldset->addField(
            'criteria_two',
            'select',
            array(
                'label' => Mage::helper('bs_kst')->__('Compliance with the guidance of maintenance materials'),
                'name'  => 'criteria_two',

            'values'=> Mage::getModel('bs_kst/ifeedback_attribute_source_criteriatwo')->getAllOptions(true),
           )
        );

        $fieldset->addField(
            'criteria_three',
            'select',
            array(
                'label' => Mage::helper('bs_kst')->__('Practical skill according to job requirements'),
                'name'  => 'criteria_three',

            'values'=> Mage::getModel('bs_kst/ifeedback_attribute_source_criteriathree')->getAllOptions(true),
           )
        );

        $fieldset->addField(
            'criteria_four',
            'select',
            array(
                'label' => Mage::helper('bs_kst')->__('Skill of using tools, instrumentation/ test including specialized equipment, the use of removable devices, perform maintenance'),
                'name'  => 'criteria_four',

            'values'=> Mage::getModel('bs_kst/ifeedback_attribute_source_criteriafour')->getAllOptions(true),
           )
        );

        $fieldset->addField(
            'criteria_five',
            'select',
            array(
                'label' => Mage::helper('bs_kst')->__('Skill of handling situations arising in maintenance'),
                'name'  => 'criteria_five',

            'values'=> Mage::getModel('bs_kst/ifeedback_attribute_source_criteriafive')->getAllOptions(true),
           )
        );

        $fieldset->addField(
            'criteria_six',
            'select',
            array(
                'label' => Mage::helper('bs_kst')->__('Complete maintenance records'),
                'name'  => 'criteria_six',

            'values'=> Mage::getModel('bs_kst/ifeedback_attribute_source_criteriasix')->getAllOptions(true),
           )
        );

        $fieldset->addField(
            'name',
            'text',
            array(
                'label' => Mage::helper('bs_kst')->__('Name'),
                'name'  => 'name',

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
        $formValues = Mage::registry('current_ifeedback')->getDefaultValues();
        if (!is_array($formValues)) {
            $formValues = array();
        }
        $defaultValues = array(
            'criteria_one' => 3,
            'criteria_two' => 3,
            'criteria_three' => 3,
            'criteria_four' => 3,
            'criteria_five' => 3,
            'criteria_six' => 3,
        );
        $formValues = array_merge($formValues, $defaultValues);



        if (Mage::getSingleton('adminhtml/session')->getIfeedbackData()) {
            $formValues = array_merge($formValues, Mage::getSingleton('adminhtml/session')->getIfeedbackData());
            Mage::getSingleton('adminhtml/session')->setIfeedbackData(null);
        } elseif (Mage::registry('current_ifeedback')) {
            $formValues = array_merge($formValues, Mage::registry('current_ifeedback')->getData());
        }
        $form->setValues($formValues);
        return parent::_prepareForm();
    }
}
